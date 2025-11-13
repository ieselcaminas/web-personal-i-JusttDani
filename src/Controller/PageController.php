<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Profesor;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProfesorRepository;
use App\Repository\InstitutoRepository;
use App\Entity\Instituto;
use App\Form\ProfesorFormType as ProfesorType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\InstitutoFormType as InstitutoType;


final class PageController extends AbstractController

{   
    #[Route('/page', name: 'app_page')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PageController.php',
        ]);
    }
       #[Route('/', name: 'inicio')]

    public function inicio(ManagerRegistry $doctrine): Response

    {
        /** @var $doctrine */
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $repositorio = $doctrine->getRepository(Profesor::class);

        $profesores = $repositorio->findAll();


        return $this->render("inicio.html.twig", ["profesores" => $profesores]);

    }
 #[Route('/institutos', name: 'inicioInstitutos')]

    public function inicioInstitutos(ManagerRegistry $doctrine): Response

    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $repositorio = $doctrine->getRepository(Instituto::class);

        $institutos = $repositorio->findAll();


        return $this->render("inicioi.html.twig", ["institutos" => $institutos]);

    }
#[Route('/instituto', name: 'instituto')]
    public function insertarConInstituto(ManagerRegistry $doctrine, Request $request)
    {
        $instituto = new Instituto();

        $formulario = $this->createForm(InstitutoType::class, $instituto);

        $formulario->handleRequest($request);



        if ($formulario->isSubmitted() && $formulario->isValid()) {

            $instituto = $formulario->getData();

            

            $entityManager = $doctrine->getManager();

            $entityManager->persist($instituto);

            $entityManager->flush();

            return $this->redirectToRoute('ficha_instituto', ['codigo' => $instituto->getId()]);

        }

        return $this->render('nuevoi.html.twig', array(

            'formulario' => $formulario->createView()

        ));
    
}



#[Route('/profesor/nuevo', name: 'nuevo')]

public function nuevo(ManagerRegistry $doctrine, Request $request) {

        $profesor = new Profesor();

        $formulario = $this->createForm(ProfesorType::class, $profesor);

        $formulario->handleRequest($request);



        if ($formulario->isSubmitted() && $formulario->isValid()) {

            $profesor = $formulario->getData();

            

            $entityManager = $doctrine->getManager();

            $entityManager->persist($profesor);

            $entityManager->flush();

            return $this->redirectToRoute('ficha_profesor', ['codigo' => $profesor->getId()]);

        }

        return $this->render('nuevo.html.twig', array(

            'formulario' => $formulario->createView()

        ));

    }
#[Route('/profesor/editar/{codigo}', name: 'editar', requirements:["codigo"=>"\d+"])]

public function editar(ManagerRegistry $doctrine, Request $request, int $codigo) {
    $repositorio = $doctrine->getRepository(Profesor::class);
    $profesor = $repositorio->find($codigo);
    
    if ($profesor){
        $formulario = $this->createForm(ProfesorType::class, $profesor);
        $formulario->handleRequest($request);
        
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $profesor = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($profesor);
            $entityManager->flush();
            return $this->redirectToRoute('ficha_profesor', ["codigo" => $profesor->getId()]);
        }
        return $this->render('nuevo.html.twig', array(

            'formulario' => $formulario->createView()
        ));
    }else{
        return $this->render('ficha_profesor.html.twig', [
            'profesor' => NULL
        ]);
    }
}


    

    #[Route('/profesor/{codigo}', name: 'ficha_profesor')]

    public function ficha(ManagerRegistry $doctrine, $codigo): Response{
        $repositorio = $doctrine->getRepository(Profesor::class);

        //Si no existe el elemento con dicha clave devolvemos null
        $profesor = $repositorio->find($codigo);

        return $this->render('ficha_profesor.html.twig', [
        'profesor' => $profesor
        ]);
    }
 
    #[Route('/profesores/buscar/{texto}', name: 'buscar_profesor')]
    public function buscar(ProfesorRepository $repositorio, $texto): Response{

        //Si no existe el elemento con dicha clave devolvemos null
        $profesor = $repositorio->findByName($texto);

        return $this->render('lista_profesor.html.twig', [
        'profesores' => $profesor
        ]);
    }

    #[Route('/profesor/update/{codigo}/{telefono}', name: 'modificar_profesor', requirements: ["codigo" => "\d+"])]
public function update(ManagerRegistry $doctrine, int $codigo, string $telefono): Response{
    $entityManager = $doctrine->getManager();
    $repositorio = $doctrine->getRepository(Profesor::class);
    $profesor = $repositorio->find($codigo); 
    
    if ($profesor){
        $profesor->setTelefono($telefono);
        try {
            $entityManager->flush();
            return $this->render('ficha_profesor.html.twig', [
                'profesor' => $profesor
            ]);
        } catch (\Exception $e) {
            return new Response('Error al modificar el profesor: ' . $e->getMessage());
        }   
    }
    return $this->render('ficha_profesor.html.twig', [
        'profesor' => null
    ]);
}

#[Route('/profesor/delete/{codigo}', name: 'eliminar_profesor', requirements: ["codigo" => "\d+"])]
public function delete(ManagerRegistry $doctrine, int $codigo): Response{
    $entityManager = $doctrine->getManager();
    $repositorio = $doctrine->getRepository(Profesor::class);
    $profesor = $repositorio->find($codigo);
    
    if ($profesor){
        try {
            $entityManager->remove($profesor);
            $entityManager->flush();
            return $this->redirectToRoute('inicio');
        } catch (\Exception $e) {
            return new Response("Error al eliminar.");
        }   
    }
    return $this->render('ficha_profesor.html.twig', [
        'profesor' => null
    ]);
}
#[Route('/instituto/{codigo}', name: 'ficha_instituto')]

    public function fichaI(ManagerRegistry $doctrine, $codigo): Response{
        $repositorio = $doctrine->getRepository(Instituto::class);
        $re = $doctrine->getRepository(Profesor::class)->findBy(['instituto' => $codigo]);

        //Si no existe el elemento con dicha clave devolvemos null
        $instituto = $repositorio->find($codigo);
        return $this->render('ficha_instituto.html.twig', ['instituto' => $instituto , 'profesores' => $re]);
    }
    #[Route('/instituto/editar/{codigo}', name: 'editar_instituto', requirements: ["codigo" => "\d+"])]
public function editarInstituto(ManagerRegistry $doctrine, Request $request, int $codigo): Response
{
    $repositorio = $doctrine->getRepository(Instituto::class);
    $instituto = $repositorio->find($codigo);
    
    if ($instituto) {
        $formulario = $this->createForm(InstitutoType::class, $instituto);
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $instituto = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($instituto);
            $entityManager->flush();

            return $this->redirectToRoute('ficha_instituto', ["codigo" => $instituto->getId()]);
        }

        return $this->render('nuevoi.html.twig', [
            'formulario' => $formulario->createView(),
        ]);
    } else {
        return $this->render('ficha_instituto.html.twig', [
            'instituto' => null,
        ]);
    }
}


#[Route('/instituto/delete/{codigo}', name: 'eliminar_instituto', requirements: ["codigo" => "\d+"])]
public function eliminarInstituto(ManagerRegistry $doctrine, int $codigo): Response
{
    $entityManager = $doctrine->getManager();
    $repositorio = $doctrine->getRepository(Instituto::class);
    $instituto = $repositorio->find($codigo);

    if ($instituto) {
        try {
            $entityManager->remove($instituto);
            $entityManager->flush();
            return $this->redirectToRoute('inicioInstitutos');
        } catch (\Exception $e) {
            return new Response("Error al eliminar el instituto: " . $e->getMessage());
        }
    }

    return $this->render('ficha_instituto.html.twig', [
        'instituto' => null
    ]);
}
#[Route('/instituto/{codigo}/profesores', name: 'profesores_instituto', requirements: ["codigo" => "\d+"])]
public function profesoresPorInstituto(ManagerRegistry $doctrine, int $codigo): Response
{
    $repositorioInstituto = $doctrine->getRepository(Instituto::class);
    $instituto = $repositorioInstituto->find($codigo);

    if (!$instituto) {
        throw $this->createNotFoundException('Instituto no encontrado');
    }

    // Opción 1: Usando la relación definida en la entidad Instituto
    $profesores = $instituto->getProfesores();

    // Opción 2 (alternativa): Buscar directamente por el campo instituto
    // $repositorioProfesor = $doctrine->getRepository(Profesor::class);
    // $profesores = $repositorioProfesor->findBy(['instituto' => $instituto]);

    return $this->render('lista_profesores_instituto.html.twig', [
        'instituto' => $instituto,
        'profesores' => $profesores
    ]);
}


}   
     
