<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Oauth Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Blogs
 *
 * @method \App\Model\Entity\Oauth get($primaryKey, $options = [])
 * @method \App\Model\Entity\Oauth newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Oauth[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Oauth|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Oauth patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Oauth[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Oauth findOrCreate($search, callable $callback = null)
 */
class OauthTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('oauth');
        $this->displayField('blog_id');
        $this->primaryKey(['blog_id', 'type']);

        $this->belongsTo('Blogs', [
            'foreignKey' => 'blog_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('type', 'create');

        $validator
            ->requirePresence('access_token', 'create')
            ->notEmpty('access_token');

        $validator
            ->requirePresence('access_secret', 'create')
            ->notEmpty('access_secret');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['blog_id'], 'Blogs'));

        return $rules;
    }
}
