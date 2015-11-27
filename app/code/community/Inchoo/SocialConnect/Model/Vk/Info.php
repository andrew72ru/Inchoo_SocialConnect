<?php

class Inchoo_SocialConnect_Model_Vk_Info extends Varien_Object
{
    protected $params = array(
        'id',
        'name',
        'first_name',
        'last_name',
//        'link',
//        'birthday',
        'sex',
        'email',
        'photo_max_orig'
    );

    protected $client = null;

    public function _construct()
    {
        parent::_construct();

        $this->client = Mage::getSingleton('inchoo_socialconnect/vk_oauth2_client');
        if(!($this->client->isEnabled()))
            return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Inchoo_SocialConnect_Model_Vk_Oauth2_Client $client)
    {
        $this->client = $client;
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function getAccessToken()
    {
        return $this->client->getAccessToken();
    }

    public function load($id = null)
    {
        $this->_load();

        return $this;
    }

    protected function _load()
    {
        try{
            $response = $this->client->api(
                '/method/users.get',
                'GET',
                array('fields' => implode(',', $this->params))
            );

            foreach ($response as $key => $value)
            {
                $this->{$key} = $value;
            }

        } catch(Inchoo_SocialConnect_Vk_OAuth2_Exception $e) {
            $this->_onException($e);
        } catch(Exception $e) {
            $this->_onException($e);
        }
    }

    protected function _onException($e)
    {
        if($e instanceof Inchoo_SocialConnect_Vk_OAuth2_Exception) {
            Mage::getSingleton('core/session')->addNotice($e->getMessage());
        } else {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }

}
