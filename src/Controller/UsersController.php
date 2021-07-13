<?php
declare(strict_types=1);

namespace App\Controller;

use App\Authenticator\Result;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\UnauthorizedException;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * @param \Cake\Event\EventInterface $event
     * @return \Cake\Http\Response|void|null
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions(['login', 'add', 'verify']);
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $identity = $this->Authentication->getIdentity()->getOriginalData();
            $this->set(compact('identity'));
        }
    }

    /**
     * @return \Cake\Http\Response|void|null
     */
    public function login()
    {
        $this->Authorization->skipAuthorization();

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $user = $this->Authentication->getIdentity();

            // If the user is logged in send them away.
            $target = $this->Authentication->getLoginRedirect() ?? '/users/view/' . $user->getIdentifier();

            return $this->redirect($target);
        }

        if ($this->request->is('post') && !$result->isValid()) {
            if ($result->getStatus() == Result::TWO_FACTOR_AUTH_FAILED) {
                // One time code was entered and it's invalid
                $this->Flash->error('Invalid 2FA code');

                return $this->redirect(['action' => 'verify']);
            } elseif ($result->getStatus() == Result::TWO_FACTOR_AUTH_REQUIRED) {
                // One time code is required and wasn't yet entered - redirect to the verify action
                return $this->redirect(['action' => 'verify']);
            } else {
                $this->Flash->error('Invalid username or password');
            }
        }
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        $this->Authorization->skipAuthorization();
        $this->Authentication->logout();

        return $this->redirect(['action' => 'login']);
    }

    /**
     * This is only used to render a view. The TwoFactorAuthenticator is doing all the heavy lifting.
     *
     * @return \Cake\Http\Response|void|null
     */
    public function verify()
    {
        $this->Authorization->skipAuthorization();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $identity = $this->request->getAttribute('identity');

        if (!$identity->can('index', $this->Users)) {
            throw new UnauthorizedException('You are not authorized to view this information.');
        }
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $identity = $this->Authentication->getIdentity();
        if (is_null($id)) {
            $id = $identity->getIdentifier();
        }

        $user = $this->Users->get($id, [
            'contain' => ['UserRoles'],
        ]);

        $this->Authorization->authorize($user);
        unset($user->password);

        $countriesTbl = $this->getTableLocator()->get('Countries');
        $countries = $countriesTbl->find()
            ->order(['(iso = "US")' => 'DESC', 'title' => 'ASC'])
            ->indexBy('iso');

        if ($this->request->is(['post'])) {
            $data = $this->request->getData();

            if ($identity->getOriginalData()->user_role->title !== 'Admin') {
                unset($data['user_role_id']);
                unset($data['user_role']);
            }

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $userRoles = [];
        if ($identity->getOriginalData()->user_role->title === 'Admin') {
            $userRoles = $this->Users->UserRoles->find('list')->toArray();
        }
        $countries = $countries->combine('iso', function($entry) {
            return '+' . $entry->calling_code . ' - ' . $entry->title;
        })->toArray();

        $this->set(compact('user', 'countries', 'userRoles'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->Authorization->skipAuthorization();

        $user = $this->Users->newEmptyEntity();
        $countriesTbl = $this->getTableLocator()->get('Countries');
        $countries = $countriesTbl->find()
            ->order(['(iso = "US")' => 'DESC', 'title' => 'ASC'])
            ->indexBy('iso');

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data['country_code'])) {
                $code = null;
                $countryArray = $countries->toArray();
                if (array_key_exists($data['country_code'], $countryArray )) {
                    $code = $countryArray[$data['country_code']]->calling_code;
                }
                $data['country_code'] = $code;
            }

            $role = $this->Users->UserRoles->find()->where(['title' => 'User'])->first();
            $data['user_role_id'] = $role->id;

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $countries = $countries->combine('iso', function($entry) {
            return '+' . $entry->calling_code . ' - ' . $entry->title;
        })->toArray();
        $this->set(compact('user', 'countries'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $this->Authorization->authorize($user);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
