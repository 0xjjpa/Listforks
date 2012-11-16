<?php

namespace ListForks\Bundle\Controller;

use ListForks\Bundle\Entity\ForkList;
use ListForks\Bundle\Entity\User;

use ListForks\Bundle\Form\Type\AccountType;
use ListForks\Bundle\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// imports the "@Secure" annotation
use JMS\SecurityExtraBundle\Annotation\Secure;

class ListsController extends Controller
{
	
	public function optionsListsAction()
    {
        return new Response('[OPTIONS] /lists');

    } // "options_lists" [OPTIONS] /lists


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function getListsAction()
    {
        return $this->render('ListForksBundle:List:index.html.twig');

    } // "get_lists"     [GET] /lists


    public function newListsAction()
    {
        return new Response('[GET] /lists/new');

    } // "new_lists"     [GET] /lists/new


    public function postListsAction()
    {
        return new Response('[POST] /lists');

    } // "post_lists"    [POST] /lists


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function getListAction($id)
    {
        return new Response('[GET] /lists/'.$id);

    } // "get_list"      [GET] /lists/{id}


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editListAction($id)
    {
        return new Response('[GET] /lists/'.$id.'/edit');

    } // "edit_list"     [GET] /lists/{id}/edit


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function putListAction($id)
    {
        return new Response('[PUT] /lists/'.$id);

    } // "put_list"      [PUT] /lists/{id}


    /**
     * @Secure(roles="ROLE_USER")
     */
    public function deleteListAction($id)
    {
        return new Response('[DELETE] /lists/'.$id);

    } // "delete_list"   [DELETE] /lists/{id}

}