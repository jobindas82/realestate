<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ledgers')->insert(
            [
                [
                    'parent_id' => 0,
                    'name' => 'Current Assets',
                    'level' => 1,
                    'root' => NULL,
                    'type' => 'A',
                    'class' => NULL,
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 0,
                    'name' => 'Fixed Assets',
                    'level' => 1,
                    'root' => NULL,
                    'type' => 'A',
                    'class' => NULL,
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 0,
                    'name' => 'Current Liabilities',
                    'level' => 1,
                    'root' => NULL,
                    'type' => 'L',
                    'class' => NULL,
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 0,
                    'name' => 'Long-term Liabilities',
                    'level' => 1,
                    'root' => NULL,
                    'type' => 'L',
                    'class' => NULL,
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 0,
                    'name' => 'Expense',
                    'level' => 1,
                    'root' => NULL,
                    'type' => 'E',
                    'class' => 'EX_P',
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 0,
                    'name' => 'Equity',
                    'level' => 1,
                    'root' => NULL,
                    'type' => 'L',
                    'class' => NULL,
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 0,
                    'name' => 'Income',
                    'level' => 1,
                    'root' => NULL,
                    'type' => 'I',
                    'class' => 'IN_P',
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 1,
                    'name' => 'Bank Accounts',
                    'level' => 2,
                    'root' => 1,
                    'type' => 'A',
                    'class' => 'BANK_P',
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 1,
                    'name' => 'Cash Accounts',
                    'level' => 2,
                    'root' => 1,
                    'type' => 'A',
                    'class' => 'CASH_P',
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 3,
                    'name' => 'Deposits',
                    'level' => 2,
                    'root' => 3,
                    'type' => 'L',
                    'class' => 'DE_P',
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 3,
                    'name' => 'Duties and Taxes',
                    'level' => 2,
                    'root' => 3,
                    'type' => 'L',
                    'class' => NULL,
                    'is_parent' => 'Y',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 2,
                    'name' => 'Buildings',
                    'level' => 2,
                    'root' => 2,
                    'type' => 'A',
                    'class' => 'BUL',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 2,
                    'name' => 'Depreciation',
                    'level' => 2,
                    'root' => 2,
                    'type' => 'A',
                    'class' => 'DPR',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 7,
                    'name' => 'Rent',
                    'level' => 2,
                    'root' => 7,
                    'type' => 'I',
                    'class' => 'RNT',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'Y',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 6,
                    'name' => 'Retained Earnings',
                    'level' => 2,
                    'root' => 6,
                    'type' => 'L',
                    'class' => 'EAR',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 5,
                    'name' => 'Unbalanced',
                    'level' => 2,
                    'root' => 5,
                    'type' => 'E',
                    'class' => 'UBL',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 9,
                    'name' => 'Cash-in-Hand',
                    'level' => 3,
                    'root' => '1,9',
                    'type' => 'A',
                    'class' => 'CASH_C',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 11,
                    'name' => 'VAT on Sales',
                    'level' => 3,
                    'root' => '3,11',
                    'type' => 'L',
                    'class' => 'VAT_S',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 11,
                    'name' => 'VAT on Expense',
                    'level' => 3,
                    'root' => '3,11',
                    'type' => 'L',
                    'class' => 'VAT_E',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 11,
                    'name' => 'VAT on Purchase',
                    'level' => 3,
                    'root' => '3,11',
                    'type' => 'L',
                    'class' => 'VAT_P',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 3,
                    'name' => 'Security Deposit',
                    'level' => 2,
                    'root' => 3,
                    'type' => 'L',
                    'class' => 'SE_D',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'Y',
                    'is_active' => 'Y',
                    'created_by' => 0
                ],
                [
                    'parent_id' => 6,
                    'name' => 'Current Profit',
                    'level' => 2,
                    'root' => 6,
                    'type' => 'L',
                    'class' => 'CPR',
                    'is_parent' => 'N',
                    'is_generated' => 'Y',
                    'is_contract_item' => 'N',
                    'is_active' => 'Y',
                    'created_by' => 0
                ]
            ]
        );

        DB::table('entry_types')->insert([
            [
                'name' => 'Receipt',
                'start' => '1000',
                'current' => '0'
            ],
            [
                'name' => 'Payment',
                'start' => '4000',
                'current' => '0'
            ],
            [
                'name' => 'Journal',
                'start' => '7000',
                'current' => '0'
            ]
        ]);

        DB::table('countries')->insert(['code' => 'AE', 'name' => 'United Arab Emirates']);
    }
}
