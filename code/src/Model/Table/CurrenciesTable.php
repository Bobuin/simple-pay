<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Currency;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Currencies Model
 *
 * @property \App\Model\Table\CurrencyRatesTable|\Cake\ORM\Association\HasMany $CurrencyRates
 * @property \App\Model\Table\WalletsTable|\Cake\ORM\Association\HasMany       $Wallets
 *
 * @method Currency get($primaryKey, $options = [])
 * @method Currency newEntity($data = null, array $options = [])
 * @method Currency[] newEntities(array $data, array $options = [])
 * @method Currency|bool save(EntityInterface $entity, $options = [])
 * @method Currency|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Currency patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Currency[] patchEntities($entities, array $data, array $options = [])
 * @method Currency findOrCreate($search, callable $callback = null, $options = [])
 */
class CurrenciesTable extends Table
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

        $this->setTable('currencies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('CurrencyRates', [
            'foreignKey' => 'currency_id',
        ]);
        $this->hasMany('Wallets', [
            'foreignKey' => 'currency_id',
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
            ->scalar('code')
            ->maxLength('code', 3)
            ->allowEmpty('code');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmpty('name');

        return $validator;
    }
}
