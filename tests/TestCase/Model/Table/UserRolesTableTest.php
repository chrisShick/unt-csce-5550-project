<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserRolesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserRolesTable Test Case
 */
class UserRolesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UserRolesTable
     */
    protected $UserRoles;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.UserRoles',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UserRoles') ? [] : ['className' => UserRolesTable::class];
        $this->UserRoles = $this->getTableLocator()->get('UserRoles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->UserRoles);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
