<?php
declare(strict_types=1);

namespace App\Test\TestCase\Logic;

use App\Logic\ReportLogic;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;

/**
 * Class ReportLogicTest
 *
 * @package App\Test\TestCase\Logic
 */
class ReportLogicTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Logic\users',
        'app.Logic\transactions',
        'app.Logic\wallets',
    ];

    public function testGetReportWrongUser(): void
    {
        $userId = 100;

        $data = [
            'id' => $userId,
            'date_from' => date('Y-m-d H:i:s', strtotime('1 day ago')),
            'date_to' => date('Y-m-d H:i:s'),

        ];

        try {
            (new ReportLogic())->getReport($data)->toArray();
        } catch (NotFoundException $exception) {
            $this->assertEquals('User with this ID is not exist.', $exception->getMessage());
        }
    }

    public function testGetReportWrongWallet(): void
    {
        $userId = 2;

        $data = [
            'id' => $userId,
            'date_from' => date('Y-m-d H:i:s', strtotime('1 day ago')),
            'date_to' => date('Y-m-d H:i:s'),

        ];

        try {
            (new ReportLogic())->getReport($data)->toArray();
        } catch (NotFoundException $exception) {
            $this->assertEquals('The requested wallet is not exists.', $exception->getMessage());
        }
    }

    public function testGetReport(): void
    {
        $userId = 1;

        $data = [
            'id' => $userId,
            'date_from' => date('Y-m-d H:i:s', strtotime('1 day ago')),
            'date_to' => date('Y-m-d H:i:s'),

        ];

        $operations = (new ReportLogic())->getReport($data)->toArray();

        $this->assertNotEmpty($operations);
        $this->assertCount(1, $operations);
    }
}
