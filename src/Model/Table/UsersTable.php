<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Behavior\Strategy\HaliteStrategy;
use Cake\Http\Client;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\UserRolesTable&\Cake\ORM\Association\BelongsTo $UserRoles
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * @var string
     */
    protected $passwordRegex = '/^(?:(?=.*\d)(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[^A-Za-z0-9])'
    . '(?=.*[a-z])|(?=.*[^A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9]))'
    . '(?!.*(.)\1{2,})[A-Za-z0-9!~<>,;:_=?*+#."&§%°()\|\[\]\-\$\^\@\/]{8,32}$/';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('UserRoles', [
            'foreignKey' => 'user_role_id',
            'joinType' => 'INNER',
        ]);

        $this->addBehavior('Crypt', [
            'fields' => ['phone' => 'string'],
            'strategy' => new HaliteStrategy('unt_project_encryption'),
            'implementedEvents' => [
                'Model.beforeSave' => 'beforeSave',
                'Model.beforeFind' => 'beforeFind',
            ],
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('country_code')
            ->maxLength('country_code', 5)
            ->notEmptyString('country_code');

        $validator
            ->scalar('phone')
            ->notEmptyString('phone')
            ->maxLength('phone', 15)
            ->add('phone', 'custom', [
                'rule' => function ($value, $context) {
                    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                    try {
                        $phone = '+' . $context['data']['country_code'] . $value;
                        $proto = $phoneUtil->parse($phone);

                        return $phoneUtil->isValidNumber($proto);
                    } catch (\libphonenumber\NumberParseException $e) {
                        return false;
                    }
                },
                'message' => 'This country code and phone number combination is not valid'
            ]);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->regex(
                'password',
                $this->passwordRegex,
                'Your password must be between 8 to 32 characters'
                . ' requiring at least 3 out 4 (uppercase and lowercase letters,'
                . ' numbers and special characters) and no more than 2 equal characters in a row.'
            )->add('password', 'pwned', [
                'rule' => function ($value, $context) {
                    $sha1 = strtoupper(sha1($value));
                    $fragment = substr($sha1, 0, 5);
                    $password = substr($sha1, 5);
                    $client = new Client();

                    $response = $client->get('https://api.pwnedpasswords.com/range/' . $fragment);

                    $body = $response->getBody()->getContents();

                    return !str_contains($body, $password);
                },
                'message' => 'Your password is not strong enough, please try another password.',
            ]);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->notEmptyString('name');

        $validator
            ->boolean('is_verified')
            ->notEmptyString('is_verified');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->existsIn(['user_role_id'], 'UserRoles'), ['errorField' => 'user_role_id']);

        return $rules;
    }

    /**
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findUser(Query $query, array $options): Query
    {
        return $query->contain(['UserRoles']);
    }
}
