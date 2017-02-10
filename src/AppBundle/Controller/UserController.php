<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends FOSRestController
{
   /**
 * @Rest\Get("/Alluser")
    *
    * This is the documentation description of your method, it will appear
    * on a specific pane. It will read all the text until the first
    * annotation.
    *
    * @ApiDoc(
    *     statusCodes={
    *          200="Returned when successful",
    *          403="Returned when the user is not authorized to say hello",
    *          404={
    *              "Returned when the user is not found",
    *              "Returned when something else is not found"
    *                },
    *            },
    *            description = "Add here some description for this method",
    *           requirements = {
    *                {"name"="name", "dataType"="string", "requirements"="\w+", "description" = "description for this parameter"},
    *     {"name"="role", "dataType"="string", "requirements"="\w+", "description" = "description for this parameter"}
    *            },
    *            cache=false,
    *            tags = {
    *                "stable" = "green",
    *                "deprecated" = "#ff0000"
    *            },
    *            deprecated = true,
    *            section = "1.All Users"
    *       )
    *  resource=true,
    *  description="This is a description of your API method",
    *  filters={
    *      {"name"="id", "dataType"="integer"},
    *     {"name"="name", "dataType"="string"},
    *      {"name"="role", "dataType"="string"},}
    * )
    */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/user/{id}")
     *
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *     statusCodes={
     *          200="Returned when successful",
     *          403="Returned when the user is not authorized to say hello",
     *          404={
     *              "Returned when the user is not found",
     *              "Returned when something else is not found"
     *                },
     *            },
     *            description = "Add here some description for this method",
     *           requirements = {
     *                {"name"="name", "dataType"="string", "requirements"="\w+", "description" = "description for this parameter"},
     *     {"name"="role", "dataType"="string", "requirements"="\w+", "description" = "description for this parameter"}
     *            },
     *            cache=false,
     *            tags = {
     *                "stable" = "green",
     *                "deprecated" = "#ff0000"
     *            },
     *            deprecated = true,
     *            section = "2.User by ID"
     *       )
     *  resource=true,
     *  description="This is a description of your API method",
     *  filters={
     *      {"name"="id", "dataType"="integer"},
     *     {"name"="name", "dataType"="string"},
     *      {"name"="role", "dataType"="string", "pattern"="(user|role) ASC|DESC"},
     *      }
     * )
     */
    public function idAction(Request $request)
    {
        $id = $request->get('id');
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }
    /**
     * @Rest\Get("/user")
     *
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     * @ApiDoc(
     *     statusCodes={
     *          200="Returned when successful",
     *          403="Returned when the user is not authorized to say hello",
     *          404={
     *              "Returned when the user is not found",
     *              "Returned when something else is not found"
     *                },
     *            },
     *            description = "Add here some description for this method",
     *requirements = {
     *                {"name"="name", "dataType"="string", "requirements"="\w+", "description" = "description for this parameter"},
     *            },
     *            cache=false,
     *            tags = {
     *                "stable" = "green",
     *                "deprecated" = "#ff0000"
     *            },
     *            deprecated = true,
     *            section = "2.User by ID"
     *       )
     *  resource=true,
     *  description="This is a description of your API method",
     * )
     */
    public function find_by_name_Action(Request $request)
    {
        $name = $request->get('name');
        $role = $request->get('role');
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(
            array('name' => $name));
        if ($singleresult === null) {
            return new View("User not found".$name."   ".$role."   ".$singleresult, Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }
    /**
     * @Rest\Post("/Adduser/")

     * @ApiDoc(
     *     statusCodes={
     *          200="Returned when successful",
     *          403="Returned when the user is not authorized to say hello",
     *          404={
     *              "Returned when the user is not found",
     *              "Returned when something else is not found"
     *                },
     *            },
     *            description = "Add new user",
     *           requirements = {
     *                {"name"="name", "dataType"="string", "requirements"="\w+", "description" = "string"},
     *     {"name"="role", "dataType"="string", "requirements"="\w+", "description" = "string"}
     *            },
     *            cache=false,
     *            tags = {
     *                "stable" = "green",
     *                "deprecated" = "#ff0000"
     *            },
     *            deprecated = true,
     *            section = "3.Add User"
     *       )
     *     resource=true,
     *  description="Create a new Object",
     *     filters={
     *     {"name"="name", "dataType"="string"},
     *     {"name"="role", "dataType"="string"}},
     * )
     *
     */
    public function postAction(Request $request)
    {
        $data = new User;
        $name = $request->get('name');
        $role = $request->get('role');
        if(empty($name) || empty($role))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setName($name);
        $data->setRole($role);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("User Added Successfully", Response::HTTP_OK);
    }
    /**
     * @Rest\Put("/user/{id}")
     *@ApiDoc(
     *     statusCodes={
     *          200="Returned when successful",
     *          403="Returned when the user is not authorized to say hello",
     *          404={
     *              "Returned when the user is not found",
     *              "Returned when something else is not found"
     *                },
     *            },
     *            description = "Update data",
     *           requirements = {
     *                {"name"="name", "dataType"="string", "requirements"="\w+", "description" = "string"},
     *     {"name"="role", "dataType"="string", "requirements"="\w+", "description" = "string"}
     *            },
     *            cache=false,
     *            tags = {
     *                "stable" = "green",
     *                "deprecated" = "#ff0000"
     *            },
     *            deprecated = true,
     *            section = "4.Update User"
     *       )
     *     resource=true,
     *  description="Create a new Object",
     *     }
     */
    public function updateAction(Request $request,$id)
    {
        $name = $request->get('name');
        $role = $request->get('role');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if($user){
            $user->setName($name);
            $user->setRole($role);
            $em->flush();
            return new View("Data updated",Response::HTTP_OK);
        }else{
            return new View("data cannot be empty",Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    /**
     * @Rest\Delete("/user/{id}")
     *@ApiDoc(
     *     statusCodes={
     *          200="Returned when successful",
     *          403="Returned when the user is not authorized to say hello",
     *          404={
     *              "Returned when the user is not found",
     *              "Returned when something else is not found"
     *                },
     *            },
     *            description = "Update data",
     *
     *            cache=false,
     *            tags = {
     *                "stable" = "green",
     *                "deprecated" = "#ff0000"
     *            },
     *            deprecated = true,
     *            section = "5.Delete User"
     *       )
     *     resource=true,
     *  description="Create a new Object",
     *     }
     */
    public function deleteAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if($user){
            $em->remove($user);
            $em->flush();
            return new View("Data deleted",Response::HTTP_OK);
        }else{
            return new View("Error",Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}