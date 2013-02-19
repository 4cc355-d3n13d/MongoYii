<?php

class User extends EMongoDocument{

	public $username;
	public $addresses = array();
	public $url = array();
	public $interests = array();

	function scopes(){
		return array(
			'programmers' => array(
				'condition' => array('job_title' => 'programmer'),
				'sort' => array('name' => 1),
				'skip' => 1,
				'limit' => 3
			)
		);
	}

	function behaviors(){
		return array(
			'EMongoTimestampBehaviour'
		);
	}

	function rules(){
		return array(
			array('addresses', 'subdocument', 'type' => 'many', 'rules' => array(
				array('road', 'string'),
				array('town', 'string'),
				array('county', 'string'),
				array('post_code', 'string'),
				array('telephone', 'integer')
			)),
			array('url', 'subdocument', 'type' => 'one', 'class' => 'SocialUrl'),
			array('_id, username, addresses', 'safe', 'on'=>'search'),
		);
	}

	function collectionName(){
		return 'users';
	}

	function relations(){
		return array(
			'interests' => array('many', 'Interest', 'i_id'),
			'one_interest' => array('one', 'Interest', 'i_id'),
			'embedInterest' => array('many', 'Interest', '_id', 'on' => 'interests'),
			'where_interest' => array('many', 'Interest', 'i_id', 'where' => array('name' => 'jogging'))
		);
	}

	function attributeLabels(){
		return array(
			'username' => 'name'
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

class SocialUrl extends EMongoModel{

	public function rules(){
		return array(
			array('url, caption', 'string'),
		);
	}
}