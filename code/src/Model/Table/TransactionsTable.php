<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Transaction;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \App\Model\Table\WalletsTable|\Cake\ORM\Association\BelongsTo $Wallets
 *
 * @method Transaction get($primaryKey, $options = [])
 * @method Transaction newEntity($data = null, array $options = [])
 * @method Transaction[] newEntities(array $data, array $options = [])
 * @method Transaction|bool save(EntityInterface $entity, $options = [])
 * @method Transaction|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Transaction patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Transaction[] patchEntities($entities, array $data, array $options = [])
 * @method Transaction findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TransactionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     *
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Wallets', [
            'foreignKey' => 'wallet_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     *
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->decimal('amount')
            ->allowEmpty('amount');

        $validator
            ->decimal('base_amount')
            ->allowEmpty('base_amount');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     *
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['wallet_id'], 'Wallets'));

        return $rules;
    }
}
