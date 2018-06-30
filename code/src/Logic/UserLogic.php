<?php
declare(strict_types=1);

namespace App\Logic;

use App\Model\Entity\User;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;

/**
 * Class UserLogic
 *
 * @package Logic
 */
class UserLogic
{
    private $usersTable;

    /**
     * UserLogic constructor.
     */
    public function __construct()
    {
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @param array $userData Received data for User creation
     *
     * @return User
     */
    public function createNewUser($userData): User
    {
        $userName = $userData['name'];
        $exists = $this->usersTable->exists(['name' => $userName]);

        if ($exists) {
            throw new BadRequestException(__('User with this name already exist.'));
        }

        $wallet = (new WalletLogic())->createWallet($userData['currency']);
        $userData['wallet_id'] = $wallet->id;

        $user = $this->usersTable->newEntity();

        $user = $this->usersTable->patchEntity($user, $userData);

        try {
            $saved = $this->usersTable->save($user);
        } catch (\Exception $exception) {
            throw new BadRequestException(__('The user could not be saved. Error: ' . $exception->getMessage()));
        }

        if (!$saved) {
            throw new BadRequestException(__('The user could not be saved. Please, try again. Error: ' .
                json_encode($user->getErrors())));
        }

        return $user;
    }
}
