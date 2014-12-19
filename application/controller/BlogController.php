<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Gregory
 * Date: 22.04.13
 * Time: 19:29
 * To change this template use File | Settings | File Templates.
 */

namespace controller;

class BlogController
{

    protected $blog = NULL;
    protected $action = NULL;

    public function __construct()
    {
        $this->blog = new \entities\Blog();
        if ($this->setAction()) {
            $this->chooseAction();
        }
    }

    public function setAction()
    {
        if (isset($_GET['action']) && $_GET['action'] != '') {
            $this->action = htmlspecialchars($_GET['action']);
            return true;
        }
        return false;
    }

    public function chooseAction()
    {

        switch ($this->action) {
            case 'list':
                $this->listAction();
                break;
            case 'new':
                $this->newAction();
                break;
            case 'delete':
                $this->deleteAction();
                break;
            case 'new_user':
                $this->newUserAction();
                break;
            case 'login':
                $this->loginAction();
                break;
            default:
                $this->indexAction();
        }
    }


    public function indexAction()
    {
        $this->blog->countBlogs();
        $this->blog->loadBlog();
    }

    public function listAction()
    {
        $this->blog->countBlogs();
        $this->blog->loadBlog();
    }

    public function newAction()
    {
        $this->blog->insertBlog();
        $this->blog->countBlogs();
        $this->blog->loadBlog();
    }

    public function deleteAction()
    {
        $this->blog->deleteBlog();
        $this->blog->countBlogs();
        $this->blog->loadBlog();
    }

    public function newUserAction()
    {
        $this->blog->newUser();
        $this->blog->countBlogs();
        $this->blog->loadBlog();
    }

    public function loginAction()
    {
        $this->blog->login();
        $this->blog->countBlogs();
        $this->blog->loadBlog();
    }
}