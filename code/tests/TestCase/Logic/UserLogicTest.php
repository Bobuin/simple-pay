<?php
declare(strict_types=1);

namespace App\Test\TestCase\Logic;

use App\Logic\UserLogic;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Class UserLogicTest
 *
 * @package App\Test\TestCase\Logic
 */
class UserLogicTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Logic\currencies',
        'app.Logic\currency_rates',
        'app.Logic\users',
        'app.Logic\wallets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    public function testCreateUserAlreadyExist(): void
    {
        $userData = [
            'name' => 'Test User',
            'country' => 'Italy',
            'city' => 'Milan',
            'currency' => 'EUR',
        ];

        (new UserLogic())->createNewUser($userData);

        try {
            (new UserLogic())->createNewUser($userData);
        } catch (BadRequestException $exception) {
            $this->assertEquals('User with this name already exist.', $exception->getMessage());
        }
    }

    public function testCreateUserLongName(): void
    {
        $userData = [
            'name' => 'Test UserTest UserTest UserTest UserTest UserTest UserTest UserTest UserTest UserTest User' .
                'Test UserTest UserTest UserTest UserTest UserTest UserTest UserTest UserTest UserTest User' .
                'Test UserTest UserTest UserTest UserTest UserTest UserTest UserTest UserTest UserTest User',
            'country' => 'Italy',
            'city' => 'Milan',
            'currency' => 'EUR',
        ];

        try {
            (new UserLogic())->createNewUser($userData);
        } catch (BadRequestException $exception) {
            $this->assertContains('The user could not be saved. Please, try again.', $exception->getMessage());
        }
    }

    public function testCreateUser(): void
    {
        $userData = [
            'name' => 'Test User',
            'country' => 'Italy',
            'city' => 'Milan',
            'currency' => 'EUR',
        ];

        $user = (new UserLogic())->createNewUser($userData);

        $newUser = $this->Users->findById($user->id)->first();

        $this->assertNotEmpty($newUser);

        $this->assertEquals($userData['name'], $newUser->name);
        $this->assertEquals($userData['country'], $newUser->country);
        $this->assertEquals($userData['city'], $newUser->city);
    }
}
