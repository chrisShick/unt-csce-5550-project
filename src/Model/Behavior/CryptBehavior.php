<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use App\Model\Behavior\Strategy\StrategyInterface;
use ArrayObject;
use Cake\Core\Exception\Exception;
use Cake\Database\Type;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\Utility\Security;

/**
 * Crypt behavior
 */
class CryptBehavior extends Behavior
{
    /**
     * Default column type used when none is defined for the field.
     */
    public const DEFAULT_TYPE = 'string';

    /**
     * Name of the default strategy the behavior falls-back to.
     */
    public const DEFAULT_STRATEGY = '\App\Model\Behavior\Strategy\DefaultStrategy';

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => null,
        'strategy' => null,
        'implementedFinders' => [
            'decrypted' => 'findDecrypted',
        ],
        'implementedMethods' => [
            'encrypt' => 'encrypt',
            'decrypt' => 'decrypt',
        ],
        'implementedEvents' => [
            'Model.beforeSave' => 'beforeSave',
        ],
    ];

    /**
     * Initialize behavior configuration.
     *
     * @param array $config Configuration passed to the behavior
     * @throws \Cake\Core\Exception\Exception
     * @return void
     */
    public function initialize(array $config): void
    {
        $config += $this->_defaultConfig;
        $this->setConfig('fields', $this->_resolveFields($config['fields']));
        $this->setConfig('strategy', $this->_resolveStrategy($config['strategy']));
    }

    /**
     * Event listener to encrypt data.
     *
     * @param \Cake\Event\Event $event Event.
     * @param \Cake\Datasource\EntityInterface $entity Entity.
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity): void
    {
        $driver = $this->_table->getConnection()->getDriver();

        foreach ($this->getConfig('fields') as $field => $type) {
            if (!$entity->has($field)) {
                continue;
            }

            $raw = $entity->get($field);
            $plain = Type::build($type)->toDatabase($raw, $driver);
            $entity->set($field, $this->encrypt($plain));
        }
    }

    /**
     * Custom finder to retrieve decrypted values.
     *
     * @param \Cake\ORM\Query $query Query.
     * @param array $options Options.
     * @return \Cake\ORM\Query
     */
    public function findDecrypted(Query $query, array $options): Query
    {
        $options += ['fields' => []];
        $mapper = function ($row) use ($options) {
            $driver = $this->_table->getConnection()->getDriver();
            foreach ($this->getConfig('fields') as $field => $type) {
                if (
                    ($options['fields'] && !in_array($field, (array)$options['fields']))
                    || !($row instanceof EntityInterface) || !$row->has($field)
                ) {
                    continue;
                }

                $cipher = $row->get($field);
                $plain = $this->decrypt($cipher);
                $row->set($field, Type::build($type)->toPHP($plain, $driver));
            }

            return $row;
        };

        $formatter = function ($results) use ($mapper) {
            return $results->map($mapper);
        };

        return $query->formatResults($formatter);
    }

    /**
     * Decrypts value after every find operation ONLY IF it was added to the
     * implemented events.
     *
     * @param \Cake\Event\Event $event Event.
     * @param \Cake\ORM\Query $query Query.
     * @param \ArrayObject $options Options.
     * @param bool $primary Whether or not this is the root query or an associated query.
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary): void
    {
        $query->find('decrypted');
    }

    /**
     * Encrypts a value using the defined key.
     *
     * @param string $plain Data to encrypt.
     * @return string
     */
    public function encrypt($plain): string
    {
        return $this->getConfig('strategy')->encrypt($plain);
    }

    /**
     * Decrypts a value using the defined key.
     *
     * @param string $cipher Cipher to decrypt.
     * @return string
     */
    public function decrypt($cipher): string
    {
        if (is_resource($cipher)) {
            $cipher = stream_get_contents($cipher);
        }

        return $this->getConfig('strategy')->decrypt($cipher);
    }

    /**
     * Implemented events.
     *
     * @return array
     */
    public function implementedEvents(): array
    {
        return (array)$this->getConfig('implementedEvents');
    }

    /**
     * Resolves configured strategy.
     *
     * @param string|\App\Model\Behavior\Strategy\StrategyInterface $strategy Strategy
     * @return mixed
     * @throws \Cake\Core\Exception\Exception
     */
    protected function _resolveStrategy($strategy)
    {
        $key = Security::getSalt();

        if (!$strategy) {
            $class = self::DEFAULT_STRATEGY;
            $strategy = new $class($key);
        }

        if (is_string($strategy) && class_exists($strategy)) {
            $strategy = new $strategy($key);
        }

        if (!($strategy instanceof StrategyInterface)) {
            throw new Exception('Invalid "strategy" configuration.');
        }

        return $strategy;
    }

    /**
     * Resolves configured fields.
     *
     * @param string|array $fields Fields to resolve.
     * @return array
     * @throws \Cake\Core\Exception\Exception
     */
    protected function _resolveFields($fields)
    {
        if (is_string($fields)) {
            $fields = [$fields];
        }

        if (!is_array($fields)) {
            throw new Exception('Invalid "fields" configuration.');
        }

        $types = array_keys(Type::getMap());

        foreach ($fields as $field => $type) {
            if (is_numeric($field) && is_string($type)) {
                unset($fields[$field]);
                $field = $type;
                $type = self::DEFAULT_TYPE;
                $fields[$field] = $type;
            }

            if (!in_array($type, $types)) {
                throw new Exception(sprintf('The field "%s" mapped type "%s" was not found.', $field, $type));
            }
        }

        return $fields;
    }
}
