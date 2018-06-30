<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Wallet;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Wallets Model
 *
 * @property \App\Model\Table\CurrencyTable|\Cake\ORM\Association\BelongsTo   $Currencies
 * @property \App\Model\Table\TransactionsTable|\Cake\ORM\Association\HasMany $Transactions
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany        $Users
 *
 * @method Wallet      get($primaryKey, $options = [])
 * @method Wallet      newEntity($data = null, array $options = [])
 * @method Wallet[]    newEntities(array $data, array $options = [])
 * @method Wallet|bool save(EntityInterface $entity, $options = [])
 * @method Wallet|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Wallet      patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Wallet[]    patchEntities($entities, array $data, array $options = [])
 * @method Wallet      findOrCreate($search, callable $callback = null, $options = [])
 */
class WalletsTable extends Table
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

        $this->setTable('wallets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Currencies', [
            'foreignKey' => 'currency_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Transactions', [
            'foreignKey' => 'wallet_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'wallet_id'
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
            ->decimal('balance')
            ->allowEmpty('balance');

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
        $rules->add($rules->existsIn(['currency_id'], 'Currencies'));

        return $rules;
    }
}
