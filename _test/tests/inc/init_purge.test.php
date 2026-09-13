<?php

class init_purge_test extends DokuWikiTest
{
    /** @var mixed */
    private $oldAuth;

    public function setUp(): void
    {
        parent::setUp();

        global $auth;
        $this->oldAuth = $auth;
        $auth = new \auth_plugin_authplain();
    }

    protected function tearDown(): void
    {
        global $auth, $INPUT, $USERINFO;

        $INPUT->remove('purge');
        $INPUT->server->remove('REMOTE_USER');
        unset($_SERVER['HTTP_REFERER']);
        $USERINFO = [];
        $auth = $this->oldAuth;
    }

    public function testAnonymousUserCannotPurge(): void
    {
        global $INPUT;

        $INPUT->set('purge', 'true');
        init_purge_request();

        $this->assertFalse($INPUT->has('purge'));
    }

    public function testNonAdminUserCannotPurge(): void
    {
        global $INPUT, $USERINFO;

        $INPUT->server->set('REMOTE_USER', 'ordinary-user');
        $USERINFO = ['grps' => ['user']];
        $INPUT->set('purge', 'true');
        init_purge_request();

        $this->assertFalse($INPUT->has('purge'));
    }

    public function testAdminUserCanPurge(): void
    {
        global $INPUT, $USERINFO;

        $INPUT->server->set('REMOTE_USER', 'testuser');
        $USERINFO = ['grps' => []];
        $INPUT->set('purge', 'true');
        $this->assertTrue(auth_isadmin(null, null, true));
        init_purge_request();

        $this->assertTrue($INPUT->has('purge'));
    }

    public function testPurgeWithReferrerIsIgnoredForAdmin(): void
    {
        global $INPUT, $USERINFO;

        $INPUT->server->set('REMOTE_USER', 'testuser');
        $USERINFO = ['grps' => []];
        $_SERVER['HTTP_REFERER'] = 'https://wiki.example.com/';
        $INPUT->set('purge', 'true');
        init_purge_request();

        $this->assertFalse($INPUT->has('purge'));
    }
}
