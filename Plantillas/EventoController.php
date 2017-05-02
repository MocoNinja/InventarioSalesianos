<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 29/11/14
 * Time: 19:35
 */

namespace YourCode\Bundle\AppBundle\Controller;
use \Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;
use YourCode\Bundle\AppBundle\Model\DocumentService;
use YourCode\Bundle\FrameworkBundle\Controller\BaseController;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use YourCode\Bundle\FrameworkBundle\Controller\FrontController;


/**
 * Clase que controla todo el tema de los eventos ( es la mas grande del proyecto )
 * Class EventoController
 * @package YourCode\Bundle\AppBundle\Controller
 */
class EventoController extends BaseController implements FrontController
{
    //private $FILE_PATH='./../files/';
    private $FILE_PATH='./files/';
    private $TEMPLATE = array();

    /**
     * Método del controlador EventoController - monta la vista evento.twig y la vista editEvent.twig
     * @param $twig
     * @param $data
     * @return mixed
     */
    public function loadMainTemplate($twig,$data)
    {
        return $this->renderView($twig, $data);
    }

    /**
     * Método del controlador EventoController - Devuelve la api con los pueblos del usuario
     * @param $appId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function doGetVillagesEventosRest($appId){
        $json = array();
        $villages = $this->get('myapp.eventService')->getVillagesByappId($appId);
        if(count($villages)>0){
            $json = $this->generateVillageApi($villages);
        }
        return $this->json($json);
    }

    /**
     * Método del controlador EventoController - genera el array con los pueblos
     * @param $villages
     * @return mixed
     */
    public function generateVillageApi($villages){
        $times=0;
        for($i = 0;$i<count($villages);$i++){
            $json['user'] = $villages[$i]['appId'];
            $appId = $villages[$i]['appId'];
                if($appId == $villages[$i]['appId']){
                    $json['pueblos'][] = $villages[$i]['name'];
                }else{
                    $appId = $villages[$i]['appId'];
                    $times++;
                }

            }
        return $json;
    }

    /**
     * Método del controlador EventoController - Monta el array con las categorías para el formulario.
     * @param $categories
     * @return array
     */
    public function getCategoryToDisplay($categories){
        $categoriesForForm=array();
        foreach($categories as $valor){
            $categoriesForForm[(int)$valor['id']] =  (string)$valor['category'];
        }
        return $categoriesForForm;
    }

    /**
     * Método del controlador EventoController - monta el array para el select de los pueblo en el formulario.
     * @param $pueblos
     * @return array
     */
    public function getVillagesToDisplay($pueblos){
        $pueblosForForm=array();
        if(!is_null($pueblos)){
            foreach($pueblos as $valor){
                $pueblosForForm[(int)$valor['id']] =  (string)$valor['name'];
            }
        }
        return $pueblosForForm;
    }

    /**
     * Hacemos una carga de todos los datos de la vista desde esta función, ya que son muchos
     * @param $NewEvent
     * @param $NewCategory
     * @param $events
     * @param $categories
     * @param $path
     * @param $user
     * @param $capabilities
     * @param $appIconData
     * @param $firefox
     * @param null $hasComarca
     * @param $search_form
     * @param null $pueblos
     * @param null $VillageForm
     * @param null $centro
     * @param $numIncidencias
     * @param $formAccionesLote
     * @param null $userLanguagesInfo
     * @return array
     */
    public function formDataTemplate($NewEvent,$NewCategory,$events,$categories,$path,$user,$capabilities,$appIconData,$firefox,$hasComarca = null,$search_form,$pueblos = null,$VillageForm = null,$centro = null,$numIncidencias,$formAccionesLote, $userLanguagesInfo = null,$fiesta)
    {
        //Controla que el user sea pueblo o no
        if(is_null($VillageForm)){
           $data =array('category_form'=>$NewCategory->createView(),'categories' => $categories,
               'event_form' => $NewEvent->createView(),'events' =>$events, 'path' => $path,'usuario' => $user,
               'capabilities' => $capabilities, "data" => $appIconData, 'firefox' => $firefox, 'hasComarca' => $hasComarca,
               'pueblos' => $pueblos, "search_form" => $search_form->createView(),'centro_latitud' => $centro[0]['centro_latitud'],
               'centro_longitud' => $centro[0]['centro_longitud'], 'numincidencias' => $numIncidencias, 'accionesLote_form' => $formAccionesLote->createView(),
               'idiomas' => $userLanguagesInfo,'fiesta'=>$fiesta
           );
       }else{
           $data =array('category_form'=>$NewCategory->createView(),'categories' => $categories,
               'event_form' => $NewEvent->createView(),'events' =>$events, 'path' => $path,'usuario' => $user,
               'capabilities' => $capabilities, "data" => $appIconData, 'firefox' => $firefox, 'hasComarca' => $hasComarca,
               'pueblos' => $pueblos, 'villageForm' => $VillageForm->createView(), "search_form" => $search_form->createView(),
               'centro_latitud' => $centro[0]['centro_latitud'],'centro_longitud' => $centro[0]['centro_longitud'],
               'numincidencias' => $numIncidencias, 'accionesLote_form' => $formAccionesLote->createView(),
               'idiomas' => $userLanguagesInfo,'fiesta'=>$fiesta
           );
       }

        return $data;
    }

    /**
     * Método del controlador EventoController - Maneja el display de la vista evento.twig y editEvent.twig
     * @param string $twig
     * @param null $eventId
     * @return mixed
     */
    public function indexAction($twig = "evento.twig",$eventId = null)
    {
        $firefox = false;
        //Chequeamos si es firefox por que este no pilla los input date y time
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
            $firefox = true;
        }

        $role = $this->getRole();
        $path = $this->getPath();
        $hasComarca = $this->hasComarca();
        $appIconData = $this->getAppIconData();
        $capabilities = $this->get_User()->getCapabilities();

        if($twig == "evento.twig"){
            $id = $this->getUser();

            if($role == "ROLE_SUBUSER"){
                $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($id);
                //Cambiamos el id
                $id = $father[0]['id'];
                //cambios si es comarca o no ( depende del padre )
                $hasComarca = $father[0]['comarca'];
            }


            //Formulario de insertar por idiomas
            $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($id);

            //Traducciones
            $categories = $this->get('myapp.eventService')->getCategories($id);
            $currentLanguage =$this->get("myapp.idiomaService")->getCurrentLanguage($id);
            $categoriesFilterByActive = $this->get('myapp.eventService')->getCategoriesActives($id);

            if(!empty($currentLanguage)){
                $categories = $this->getTextos($currentLanguage[0]['idIdioma'],$categories,'idCategory','textos_category');
                $categoriesFilterByActive = $this->getTextos($currentLanguage[0]['idIdioma'],$categoriesFilterByActive,'idCategory','textos_category');
            }

            $categoriesForForm = $this->getCategoryToDisplay($categoriesFilterByActive);

            //Traducciones
            $eventos = $this->get('myapp.eventService')->getEventsToDisplay($id,true,$hasComarca,true);//ddd($eventos);
            //Eventos son especiales y la funcion esta hay que afinarla
            $currentLanguage =$this->get("myapp.idiomaService")->getCurrentLanguage($id);
            if(!empty($currentLanguage)){
                $eventos = $this->getTextos($currentLanguage[0]['idIdioma'],$eventos,'idEvento','textos_eventos');
            }
            //Por cada evento le tengo que meter la categoría traducida si la tiene
            for($e = 0;$e<count($eventos);$e++){
                $categoryId = $eventos[$e]['idcategory'];
                $traducciones = $this->get("myapp.idiomaService")->getCategoryTranslateById($categoryId);
                if(!empty($traducciones)){
                    if(empty($eventos[$e]['category']) && !is_null($traducciones[0]['category'])){
                        $eventos[$e]['category'] = $traducciones[0]['category'];
                    }
                }
            }

            //Formulario para insertar traducciones
            $formCategory= $this->initNewCategoryForm($userLanguagesInfo);

            $userSurname = $this->get('myapp.userProvider')->getUserSurname($id);
            $search_form = $this->initSearchEvent();
            $centro = $this->get('myapp.eventService')->getCentro($id);
            $numIncidencias = $this->getIncidenciasNoChecked($id);

            $accionesLote = $this->accionsToSelect($this->get('myapp.accionesloteService')->getAcciones());
            $formAccionesLote = $this->initNewAccionesLoteForm($accionesLote);
            if ($capabilities['micro_eventos']){
                $fiesta='fiesta';
            }else{
                $fiesta='evento';
            }

            //Controlamos user con pueblos
            if($hasComarca){
                $VillageForm = $this->initNewVillageForm();
                $pueblos = $this->get('myapp.eventService')->getVillages($id,false);
                if($role == "ROLE_USER"){
                    $pueblos1 = $this->get('myapp.eventService')->getVillages($id,true);
                }else{

                    $villageId = $this->get('myapp.userProvider')->getSubVillages($id);
                    $pueblos1 = $this->get('myapp.eventService')->getVillagesForSubuser($villageId[0]['villageId']);
                }
                $pueblosToSelect = $this->getVillagesToDisplay($pueblos1);

                //FORM CON LOS IDIOMAS
                $formEvent= $this->initNewEventForm($userLanguagesInfo,$categoriesForForm,$pueblosToSelect);

                //Suscriptores
                for($ev = 0;$ev<count($eventos);$ev++){
                    $eventos[$ev]['subscribers'] = $this->get("myapp.eventService")->getSubscribers($eventos[$ev]['id']);
                }

                $this->TEMPLATE = $this->formDataTemplate($formEvent,$formCategory,$eventos,$categories,$path,$userSurname,$capabilities,$appIconData,$firefox,$hasComarca,
                $search_form,$pueblos,$VillageForm,$centro,$numIncidencias,$formAccionesLote,$userLanguagesInfo,$fiesta);
            }else{
                //FORM CON LOS IDIOMAS
                $formEvent= $this->initNewEventForm($userLanguagesInfo,$categoriesForForm);

                //Suscriptores

                for($ev = 0;$ev<count($eventos);$ev++){
                    $eventos[$ev]['subscribers'] = $this->get("myapp.eventService")->getSubscribers($eventos[$ev]['id']);
                }



                $this->TEMPLATE = $this->formDataTemplate($formEvent,$formCategory,$eventos,$categories,$path,$userSurname,$capabilities,$appIconData,$firefox,$hasComarca,
                $search_form,null,null,$centro,$numIncidencias,$formAccionesLote,$userLanguagesInfo,$fiesta);
            }

            return  $this->loadMainTemplate($twig,$this->TEMPLATE);
        }else{
            //Renderizamos vista editEvent.twig
            $id = $this->getUser();

            if($role == "ROLE_SUBUSER"){
                $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($id);
                //Cambiamos el id
                $id = $father[0]['id'];
                //cambios si es comarca o no ( depende del padre )
                $hasComarca = $father[0]['comarca'];
            }


            //Formulario de insertar por idiomas
            $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($id);
            $evento = $this->get('myapp.eventService')->getEventsToDisplayById($id,$eventId,$hasComarca);


            //Eventos son especiales y la funcion esta hay que afinarla
            $currentLanguage =$this->get("myapp.idiomaService")->getCurrentLanguage($id);
            if(!empty($currentLanguage)){
                $evento = $this->getTextos($currentLanguage[0]['idIdioma'],$evento,'idEvento','textos_eventos');
                $eventoTranslate = array();
               foreach($userLanguagesInfo as $key => $v){
                    $eventoTranslate[] = $this->getTextos($v['idIdioma'],$evento,'idEvento','textos_eventos');
                }
                $evento['edit'] = $eventoTranslate;
            }

            $categoriesFilterByActive = $this->get('myapp.eventService')->getCategoriesActives($id);
            if(!empty($currentLanguage)){
                $categoriesFilterByActive = $this->getTextos($currentLanguage[0]['idIdioma'],$categoriesFilterByActive,'idCategory','textos_category');
            }

            $categoriesForForm = $this->getCategoryToDisplay($categoriesFilterByActive);

            if($role == "ROLE_USER"){
                $pueblos = $this->get('myapp.eventService')->getVillages($id);
            }else{

                if($hasComarca){
                    $villageId = $this->get('myapp.userProvider')->getSubVillages($id);
                    $pueblos = $this->get('myapp.eventService')->getVillagesForSubuser($villageId[0]['villageId']);
                }else{
                    $pueblos = null;
                }
            }

            $pueblosToSelect = $this->getVillagesToDisplay($pueblos);
            $userSurname = $this->get('myapp.userProvider')->getUserSurname($id,$role);
            $centro = $this->get('myapp.eventService')->getCentro($id);

            $formEditEvent= $this->initNewEditEventForm($userLanguagesInfo,$evento,$categoriesForForm,$pueblosToSelect,$hasComarca);
            $numIncidencias = $this->getIncidenciasNoChecked($id);

            $eventoActive=$this->get('myapp.eventService')->checkEventActive($eventId);
            return  $this->loadMainTemplate($twig,array(
            'eventEdit_form' => $formEditEvent->createView(),
            'evento' => $evento,
            'id' => $eventId,
            'path' => $path,
            'usuario' => $userSurname,
            'capabilities' => $capabilities,
            'data' => $appIconData,
            'firefox' => $firefox,
            'hasComarca' => $hasComarca,
            'centro_latitud' => $centro[0]['centro_latitud'],
            'centro_longitud' => $centro[0]['centro_longitud'],
            'numincidencias' => $numIncidencias,
            'idiomas' => $userLanguagesInfo,
            'eventoActive' => $eventoActive,
            ));

        }

    }

    /**
     * Método del controlador EventoController - api con los eventos de fiestas
     * @param $appId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function doGetEventosPartyRest($appId,Request $request){
        $json = array();
        try{
            if($this->hasComarcaAppId($appId)){
                $eventosUser = $this->get('myapp.eventService')->getEventsArrayVillage($appId,true,1);
                $eventosSubUser =$this->get('myapp.eventService')->getEventsArrayVillageSubuser($appId,true,1);
                $eventos = array_merge($eventosUser,$eventosSubUser);
            }else{
                $eventos = $this->get('myapp.eventService')->getEventsArray($appId,true,1);
            }
            $userId = $this->getUserIdByAppId($appId);
            $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($userId);
            if(count($eventos) >0){
                if(!empty($userLanguagesInfo)){
                    $eventos = $this->getTextosApi($userLanguagesInfo,$eventos,"idEvento","textos_eventos");
                    //Traducimos las categorías aqui, asi dejamos la función para todas las apis
                    for($a = 0;$a<count($eventos);$a++){
                        if(!empty($eventos[$a]['languages'])){
                            $c = 0;
                            foreach($eventos[$a]['languages'] as $key => $v){
                                $eventos[$a]['languages'][$key][0]['categoria'] = $this->get("myapp.eventService")->translateCategory($userLanguagesInfo[$c]['idIdioma'],$eventos[$a]['category_id']);
                                $c++;

                            }
                        }
                    }
                }
                $json = $this->generateEventosApi($eventos,"fiesta");
                //TENEMOS QUE CONTROLAR EL ORDEN AQUI, LA BASE DE DATOS, NO SE TOCA!!!!!!
                $order = $this->get("myapp.userProvider")->getUserOrder($userId);
                switch($order[0]['fiestas_order']){
                    case "DESC":
                        $json = array_reverse($json);
                        break;

                    case "ASC":
                        //CASO NORMAL, no hacer nada
                        break;
                }

            }

        }catch (\Exception $exception){
            return $this->json(array($exception->getMessage()));
        }

        return $this->json($json);
    }

    /**
     * Método del controlador EventoController - api con los eventos normales
     * @param $appId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function doGetEventosCulturalRest($appId,Request $request){
        $json = array();
        try{
            $userId = $this->getUserIdByAppId($appId);
            $order = $this->get("myapp.userProvider")->getUserOrder($userId);
            if ((boolean)$order[0]['eventos_pasados']){
                $eventos = $this->get('myapp.eventService')->getEventsArray($appId,true,0);
                $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($userId);
                if(count($eventos) >0){
                    if(!empty($userLanguagesInfo)){
                        $eventos = $this->getTextosApi($userLanguagesInfo,$eventos,"idEvento","textos_eventos");
                        //Traducimos las categorías aqui, asi dejamos la función para todas las apis
                        for($a = 0;$a<count($eventos);$a++){
                            if(!empty($eventos[$a]['languages'])){
                                $c = 0;
                                foreach($eventos[$a]['languages'] as $key => $v){
                                    $eventos[$a]['languages'][$key][0]['categoria'] = $this->get("myapp.eventService")->translateCategory($userLanguagesInfo[$c]['idIdioma'],$eventos[$a]['category_id']);
                                    $c++;
                                }
                            }
                        }
                    }
                    $json = $this->generateEventosApi($eventos,"culturales");
                                        //TENEMOS QUE CONTROLAR EL ORDEN AQUI, LA BASE DE DATOS, NO SE TOCA!!!!!!!!!

                    switch($order[0]['events_order']){
                        case "DESC":
                            //CASO NORMAL, no hacer nada
                            break;

                        case "ASC":
                            $json = array_reverse($json);
                            break;
                    }

                }
            }else{
                $hoy = date("Y-m-d h:i:s");
                $eventos = $this->get('myapp.eventService')->getEventsArraybyfecha($appId,true,0,$hoy);
                $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($userId);
                if(count($eventos) >0){
                    if(!empty($userLanguagesInfo)){
                        $eventos = $this->getTextosApi($userLanguagesInfo,$eventos,"idEvento","textos_eventos");
                        //Traducimos las categorías aqui, asi dejamos la función para todas las apis
                        for($a = 0;$a<count($eventos);$a++){
                            if(!empty($eventos[$a]['languages'])){
                                $c = 0;
                                foreach($eventos[$a]['languages'] as $key => $v){
                                    $eventos[$a]['languages'][$key][0]['categoria'] = $this->get("myapp.eventService")->translateCategory($userLanguagesInfo[$c]['idIdioma'],$eventos[$a]['category_id']);
                                    $c++;
                                }
                            }
                        }
                    }
                    $json = $this->generateEventosApi($eventos,"culturales");
                    //TENEMOS QUE CONTROLAR EL ORDEN AQUI, LA BASE DE DATOS, NO SE TOCA!!!!!!!!!

                    switch($order[0]['events_order']){
                        case "DESC":
                            //CASO NORMAL, no hacer nada
                            break;

                        case "ASC":
                            $json = array_reverse($json);
                            break;
                    }

                }

            }
        }catch (\Exception $exception){
            return $this->json(array($exception->getMessage()));
        }
        return $this->json($json);
    }

    /**
     * Método del controlador EventoController - api con las categorias de los eventos
     * @param $appId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function doGetCategoryEventosRest($appId,Request $request){
        $json = array();
        $categories = $this->get('myapp.eventService')->getCategoryApi($appId);

        $userId = $this->getUserIdByAppId($appId);
        $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($userId);
        if(count($categories)>0){
            if(!empty($userLanguagesInfo)){
                $categories = $this->getTextosApi($userLanguagesInfo,$categories,"idCategory","textos_category");
            }
            $json = $this->generateArrayApi($categories);
        }
        return $this->json($json);
    }

    /**
     * Método del controlador EventoController - montamos el array de la api de categorias
     * @param $categories
     * @return array
     */
    public function  generateArrayApi($categories){
        $json = array();
        $e=2;
        $path = $this->getPath();
        for($i = 0; $i<count($categories);$i++){
            $json[$i]['id'] =$categories[$i]['id'];;
            $json[$i]['name'] = $categories[$i]['category'];
            $json[$i]['checked'] = true;
            $json[$i]['icon'] = $path.$categories[$i]['icon'];
            $json[$i]['active'] = (boolean)$categories[$i]['active'];
            $json[$i]['languages'] = isset($categories[$i]['languages']) ? $categories[$i]['languages'] : null;
            $e++;
        }
        return $json;
    }

    /**
     * Método del controlador EventoController - Logaritmo que gestiona la creación del array de eventos, tanto de fiestas como normales
     * @param $eventos
     * @param $eventType
     * @return array
     */
    public function generateEventosApi($eventos,$eventType){
        $c = 0;
        $id = 1;
        $fecha = $eventos[0]['date'];
        $hasPueblos = false;
        if(isset($eventos[0]['pueblo_id'])){
            $hasPueblos = true;
        }
        $d = 0;
        $path = $this->getPath();
        $array_temp=array();
        $eventosOrdered = $eventos;
        //Generamos el json de los eventos
        for($a = 0;$a<count($eventosOrdered) ;$a++){
            $d++;
            //La primera cabecera hay que meterla de este modo porque sino el algoritmo no lo hace.
            if($fecha == $eventosOrdered[$a]['date']){
                if($d === 1){
                    $array_temp[$c]['short_name_month'] = $eventosOrdered[$a]['short_name_month'];
                    $array_temp[$c]['short_date'] = $eventosOrdered[$a]['short_date'];
                    $array_temp[$c]['dayName'] = $eventosOrdered[$a]['dayName'];
                    $array_temp[$c]['date'] = $eventosOrdered[$a]['date'];
                    $array_temp[$c]['id'] =$c+1;
                }
                //Eventos que se pueden mostrar en la app dependen de su fecha y si estan active=true
                if($eventosOrdered[$a]['done']<=0 && $eventosOrdered[$a]['active'] ==1){
                    $show = true;
                }else{
                    $show = false;
                }
                //Generamos el array de events del día
                $array_temp[$c]['events'][]= $this->loadEventData($eventosOrdered,$a,$show,$id,$path,$hasPueblos);
                if(count($eventos)-1 == $a){
                    $array_temp[$c]['short_name_month'] = $eventosOrdered[$a]['short_name_month'];
                    $array_temp[$c]['short_date'] = $eventosOrdered[$a]['short_date'];
                    $array_temp[$c]['dayName'] = $eventosOrdered[$a]['dayName'];
                    $array_temp[$c]['date'] = $eventosOrdered[$a]['date'];

                    $eventos_tmp = $array_temp[$c]['events'];
                    $array_temp[$c]['events'] = $this->repositionEvents($eventos_tmp);
                }
            $id++;
            }else {
                //saltamos de día.
                $fecha = $eventosOrdered[$a]['date'];
                //RECOLOCAR los eventos del último día introducido, eventos nocturnos
                $eventos_tmp = $array_temp[$c]['events'];
                $array_temp[$c]['events'] = $this->repositionEvents($eventos_tmp);
                //CONTADORES
                $a--;
                $c++;
                $d = 0;
            }
        }
        if($eventType == "culturales"){
            return array_reverse($array_temp);
        }else{
            return $array_temp;
        }

    }

    /**
     * Método del controlador EventoController - Reposiciona los eventos nocturnos ( son otro día tecnicamente pero coloquialmente se colocan en el mismo día )
     * @param $eventos_tmp
     * @return array
     */
    public function repositionEvents($eventos_tmp){
        $array_madrugada = array();

        for ($e = 0; $e < count($eventos_tmp); $e++) {
            //$f = new \DateTime($eventos_tmp[$e]['horaInicial']);
            $f = new \DateTime($eventos_tmp[$e]['hora']);
            if ($f->format("H:i") >= ("00:00") && $f->format("H:i") < ("06:00")){
                $array_madrugada[] = $eventos_tmp[$e];
            }
        }
        if(count($array_madrugada) > 0){

            $array_diff = array_diff_key($eventos_tmp, $array_madrugada);
            $eventosOrderedByTime = array();

            foreach ($array_diff as $eventos_de_dia) {
                $eventosOrderedByTime[] = $eventos_de_dia;
            }

            foreach ($array_madrugada as $eventos_madrugada) {
                $eventosOrderedByTime[] = $eventos_madrugada;
            }

            return $eventosOrderedByTime;

        }else{
            return $eventos_tmp;
        }
    }

    /**
     * Método del controlador EventoController - Cargamos los datos internos de cada evento
     * @param $eventosOrdered
     * @param $a
     * @param $show
     * @param $id
     * @param $path
     * @param $hasPueblos
     * @return array
     */
    public function loadEventData($eventosOrdered,$a,$show,$id,$path,$hasPueblos){
        if($hasPueblos){
            $data = array(
                'id'=>$id,
                //'fechaInicial' => $eventosOrdered[$a]['fechaSpanish'],
                //'horaInicial' => $eventosOrdered[$a]['hora'],
                'fecha' => $eventosOrdered[$a]['fechaSpanish'],
                'hora' => $eventosOrdered[$a]['hora'],
                'fechaFinal' => $eventosOrdered[$a]['fechaSpanishFinal'],
                'horaFinal' => $eventosOrdered[$a]['horaFinal'],
                'natural_id' =>$eventosOrdered[$a]['id'],
                'name'=>$eventosOrdered[$a]['titulo'],
                'category_id' => $eventosOrdered[$a]['category_id'],
                'category' => $eventosOrdered[$a]['category'],
                'organizacion' => $eventosOrdered[$a]['organizacion'],
                'foto_avatar' => $path.$eventosOrdered[$a]['foto_avatar'],
                'foto_destacada' => $path.$eventosOrdered[$a]['foto_destacada'],
                'location'  => $eventosOrdered[$a]['location'],
                'subscribe'  => (boolean)$eventosOrdered[$a]['subscribe'],
                'descripcion' => $eventosOrdered[$a]['descripcion'],
                'link' => $eventosOrdered[$a]['link'],
                'latitud' => $eventosOrdered[$a]['latitud'],
                'longitud' => $eventosOrdered[$a]['longitud'],
                'show' => $show,
                'pueblo_id' => $eventosOrdered[$a]['pueblo_id'],
                'pueblo_name' => $eventosOrdered[$a]['pueblo_name'],
                'url' => $eventosOrdered[$a]['url'],
                'languages' => isset($eventosOrdered[$a]['languages']) ? $eventosOrdered[$a]['languages'] : null
            );
        }else{
            $data = array(
                'id'=>$id,
                //'fechaInicial' => $eventosOrdered[$a]['fechaSpanish'],
                //'horaInicial' => $eventosOrdered[$a]['hora'],
                'fecha' => $eventosOrdered[$a]['fechaSpanish'],
                'hora' => $eventosOrdered[$a]['hora'],
                'fechaFinal' => $eventosOrdered[$a]['fechaSpanishFinal'],
                'horaFinal' => $eventosOrdered[$a]['horaFinal'],
                'natural_id' =>$eventosOrdered[$a]['id'],
                'name'=>$eventosOrdered[$a]['titulo'],
                'category_id' => $eventosOrdered[$a]['category_id'],
                'category' => $eventosOrdered[$a]['category'],
                'organizacion' => $eventosOrdered[$a]['organizacion'],
                'foto_avatar' => $path.$eventosOrdered[$a]['foto_avatar'],
                'foto_destacada' => $path.$eventosOrdered[$a]['foto_destacada'],
                'location'  => $eventosOrdered[$a]['location'],
                'subscribe'  => (boolean)$eventosOrdered[$a]['subscribe'],
                'descripcion' => $eventosOrdered[$a]['descripcion'],
                'link' => $eventosOrdered[$a]['link'],
                'latitud' => $eventosOrdered[$a]['latitud'],
                'longitud' => $eventosOrdered[$a]['longitud'],
                'url' => $eventosOrdered[$a]['url'],
                'show' => $show,
                'languages' => isset($eventosOrdered[$a]['languages']) ? $eventosOrdered[$a]['languages'] : null
            );
        }
        return $data;
    }

    /**
     * Método del controlador EventoController - monta el formulario de creación de eventos
     * @param array $languages
     * @param null $categories
     * @param null $pueblos
     * @return mixed
     */
    public function initNewEventForm($languages = array(),$categories = null,$pueblos = null)
    {
        if(is_null($pueblos)){
            //Ñapa para cuando no hay pueblos
            $pueblos = array(0 =>"");
        }

        $capabilities = $this->get_User()->getCapabilities();
        if ($capabilities['micro_eventos']){
            $fiesta='fiesta';
        }else{
            $fiesta='evento';
        }
        $id = $this->getUser();
        $data = $this->get('myapp.userProvider')->getUserByID($id);
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("programaFiestas","checkbox", array(
                "required" => true,
                "label" => "¿Pertenece al programa de fiestas?",
                'label_attr' => array(
                    'class' => 'block',
                ),
                "attr" => array(
                    "class" => "form-control",
                    'data-toggle' => 'toggle',
                    'data-on' => "Si",
                    'data-off' => "No",
                    'data-onstyle'=>"info",
                    "intabindex"=>3
                )
            ))
            ->add("subscribe","checkbox", array(
                "required" => false,
                "label" => "¿Permitir suscripciones?",
                'label_attr' => array(
                    'class' => 'block'
                ),
                "attr" => array(
                    "class" => "form-control",
                    'data-toggle' => 'toggle',
                    'data-on' => "Si",
                    'data-off' => "No",
                    'data-onstyle'=>"info",
                )
            ))
            ->add("pueblos","choice", array(
                "required" => true,
                "label" => "Pueblos",
                'choices' => $pueblos,
                "attr" => array(
                    "class" => "form-control",
                    "intabindex"=>5
                )
            ))
            ->add("fechaInicial","datetime", array(
                "required" => true,
                "label" => "Fecha de inicio del evento",
                'date_widget' => 'single_text',
                'time_widget' => "single_text",
                //'format' => 'Y-M-D H:i',
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Día y hora del evento",
                    "intabindex"=>6
                )
            ))
            ->add("fechaFinal","datetime", array(
                "required" => false,
                "label" => "Fecha de finalización del evento",
                "label_attr" => array(
                    "class" => "margin-bottom-0"
                ),
                'date_widget' => 'single_text',
                'time_widget' => "single_text",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Día y hora en la que finaliza el evento",
                    "intabindex"=>7
                )
            ))
            ->add("fechaPublicacion","datetime", array(
                "required" => false,
                "label" => "Fecha de publicación del evento",
                "label_attr" => array(
                    "class" => "margin-bottom-0"
                ),
                'date_widget' => 'single_text',
                'time_widget' => "single_text",
                //'format' => 'Y-M-D H:i',
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Fecha de publicación",
                    "intabindex"=>14
                )
            ))
            ->add("categories","choice", array(
                "required" => true,
                "label" => "Categoría",
                'choices' => $categories,
                "attr" => array(
                    "class" => "form-control",
                    "intabindex"=>8
                )
            ))
            ->add("latitud","text", array(
                "required" => false,
                "label" => "Localización - Latitud",
                "empty_data" => 0,
                "data"=> $data[0]['centro_latitud'],
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Escriba la latitud",
                    "class" => "form-control",
                    "intabindex"=>10
                )
            ))
            ->add("longitud","text", array(
                "required" => false,
                "label" => "Localización - Longitud",
                "empty_data" => 0,
                'data'=>$data[0]['centro_longitud'],
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Escriba la longitud",
                    "class" => "form-control",
                    "intabindex"=>11
                )
            ))
            ->add("link","text", array(
                "required" => false,
                "label" => "Link para información extra",
                "trim" => true,
                "data"=> "http://",
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Link para información extra",
                    "class" => "form-control",
                    "intabindex"=>11
                )
            ))
            ->add("foto_principal","file", array(
                "required" => false,
                "label" => "Foto principal",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Foto principal",
                    "class" => "file",
                    "intabindex"=>12
                )
            ))
            ->add("foto_miniatura","file", array(
                "required" => false,
                "label" => "Foto miniatura",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Foto miniatura",
                    "class" => "file",
                    "intabindex"=>13
                )
            ))
            ->add("copias","integer", array(
                "required" => true,
                "label" => "Número de copias",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "class" => "form-control double_number",
                    'min' => 1,
                    'max' =>100,
                    'width'=>3,
                    "value"=>1,
                ),

            ))
            ->setMethod('POST');

        for($lang = 0;$lang<count($languages);$lang++){
            $form->add("titulo_{$languages[$lang]['idIdioma']}","text", array(
                "required" => true,
                "label" => "Título",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Título del evento",
                    "class" => "form-control",
                    "intabindex"=>1
                )
            ))
            ->add("descripcion_{$languages[$lang]['idIdioma']}","textarea", array(
                "required" => true,
                "label" => "Descripción",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 1000,
                    "placeholder" => "Descripción del evento",
                    "class" => "form-control",
                    "intabindex"=>2
                )
            ))
            ->add("organizacion_{$languages[$lang]['idIdioma']}","text", array(
                "required" => true,
                "label" => "Organización del evento",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Escriba quién organiza este evento.",
                    "class" => "form-control",
                    "intabindex"=>4
                )
            ))
            ->add("lugar_{$languages[$lang]['idIdioma']}","text", array(
                "required" => true,
                "label" => "Lugar del evento",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Escriba el lugar donde se desarrollará el evento",
                    "class" => "form-control",
                    "intabindex"=>9
                )
            ))
            ->add("url_{$languages[$lang]['idIdioma']}","text", array(
                "required" => false,
                "label" => "Video",
                "trim" => true,
                "empty_data" => null,
                "attr" => array(
                    "maxlength" => 300,
                    "placeholder" => "Escriba la dirección de un video de youtube.",
                    "class" => "form-control"
                )
            ));
        }


        return $form->getForm();
    }


    /**
     * Método del controlador EventoController - Monta el formulario de edición de eventos
     * @param array $languages
     * @param null $data
     * @param null $categories
     * @param null $pueblos
     * @param $hasComarca
     * @return mixed
     */
    public function initNewEditEventForm($languages = array(),$data = null,$categories = null,$pueblos = null,$hasComarca)
    {

        if($hasComarca){
            $form = $this->get('form.factory')->createBuilder('form')
                ->add("programaFiestas","checkbox", array(
                    "required" => false,
                    "label" => "¿Pertenece al programa de fiestas?",
                    'label_attr' => array(
                        'class' => 'block'
                    ),
                    'data' => (boolean)$data[0]['programaFiestas'],
                    "attr" => array(
                        "class" => "form-control",
                        'data-toggle' => 'toggle',
                        'data-on' => "Si",
                        'data-off' => "No",
                        'data-onstyle'=>"info"
                    )
                ))
                ->add("subscribe","checkbox", array(
                    "required" => false,
                    "label" => "¿Permitir suscripciones?",
                    'label_attr' => array(
                        'class' => 'block'
                    ),
                    'data' => (boolean)$data[0]['subscribe'],
                    "attr" => array(
                        "class" => "form-control",
                        'data-toggle' => 'toggle',
                        'data-on' => "Si",
                        'data-off' => "No",
                        'data-onstyle'=>"info"
                    )
                ))
                ->add("pueblos","choice", array(
                    "required" => true,
                    "label" => "Pueblos",
                    "data" => (int)$data[0]['puebloId'],
                    'choices' => $pueblos,
                    "attr" => array(
                        "class" => "form-control"
                    )
                ))
                ->add("fechaEditEvent","datetime", array(
                    "required" => false,
                    "label" => "Fecha inicial",
                    'date_widget' => 'single_text',
                    'time_widget' => "single_text",
                    'data' =>new \DateTime($data[0]['fecha'])
                ))
                ->add("fechaEditEvent_final","datetime", array(
                    "required" => false,
                    "label" => "Fecha final",
                    'date_widget' => 'single_text',
                    'time_widget' => "single_text",
                    'data' => is_null($data[0]['fecha_final']) ? null : new \DateTime($data[0]['fecha_final'])
                ))
                ->add("fechaEditEvent_publicacion","datetime", array(
                    "required" => false,
                    "label" => "Fecha de publicación",
                    'date_widget' => 'single_text',
                    'time_widget' => "single_text",
                    "empty_data" => null,
                    'data' =>is_null($data[0]['fecha_publicacion']) ? null : new \DateTime($data[0]['fecha_publicacion'])
                ))
                ->add("categories","choice", array(
                    "required" => true,
                    "label" => "Categoría",
                    "data" => $data[0]['idcategory'],
                    'choices' => $categories,
                    "attr" => array(
                        "class" => "form-control"
                    )
                ))
                ->add("latitud","text", array(
                    "required" => false,
                    "label" => "Localización - Latitud",
                    "data" => $data[0]['latitud'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba la latitud",
                        "class" => "form-control"
                    )
                ))
                ->add("longitud","text", array(
                    "required" => false,
                    "label" => "Localización - Longitud",
                    "data" => $data[0]['longitud'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba la longitud",
                        "class" => "form-control"
                    )
                ))
                ->add("link","text", array(
                    "required" => false,
                    "label" => "Link para información extra",
                    //"data"=> "http://",
                    "data" => is_null($data[0]['link']) ? "http://" : $data[0]["link"],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Link para información extra",
                        "class" => "form-control"
                    )
                ))
                ->add("foto_principal","file", array(
                    "required" => false,
                    "label" => "Foto principal",
                    "empty_data" => null,
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Foto principal",
                        "class" => "file"
                    )
                ))
                ->add("foto_miniatura","file", array(
                    "required" => false,
                    "label" => "Foto miniatura",
                    "empty_data" => null,
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Foto miniatura",
                        "class" => "file"
                    )
                ))
                ->add('id','hidden',array(
                    'required' => true,
                    "data" => $data[0]['id']
                ))
                ->setMethod('POST');

            for($lang = 0;$lang<count($languages);$lang++){
                $form->add("titulo_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Título",
                    "data" => $data['edit'][$lang][0]["titulo"],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => " Título del evento",
                        "class" => "form-control"
                    )
                ))
                ->add("descripcion_{$languages[$lang]['idIdioma']}","textarea", array(
                    "required" => false,
                    "label" => "Descripción",
                    "data" => $data['edit'][$lang][0]['descripcion'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 1000,
                        "placeholder" => "Descripción del evento",
                        "class" => "form-control"
                    )
                ))
                ->add("organizacion_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Organización del evento",
                    "data" => $data['edit'][$lang][0]['organizacion'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba quién organiza este evento.",
                        "class" => "form-control"
                    )
                ))
                ->add("lugar_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Lugar del evento",
                    "data" => $data['edit'][$lang][0]['lugar'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba el lugar donde se desarrollará el evento",
                        "class" => "form-control"
                    )
                ))
                ->add("url_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Video",
                    "data" => $data['edit'][$lang][0]['url'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 300,
                        "placeholder" => "Escriba la dirección de un video de youtube.",
                        "class" => "form-control"
                    )
                ));
            }

          return  $form->getForm();
        }else{
            $form = $this->get('form.factory')->createBuilder('form')
                ->add("programaFiestas","checkbox", array(
                    "required" => false,
                    "label" => "¿Pertenece al programa de fiestas?",
                    'label_attr' => array(
                        'class' => 'block'
                    ),
                    'data' => (boolean)$data[0]['programaFiestas'],
                    "attr" => array(
                        "class" => "form-control",
                        'data-toggle' => 'toggle',
                        'data-on' => "Si",
                        'data-off' => "No",
                        'data-onstyle'=>"info"
                    )
                ))
                ->add("fechaEditEvent","datetime", array(
                    "required" => false,
                    "label" => "Fecha",
                    'date_widget' => 'single_text',
                    'time_widget' => "single_text",
                    'format' => 'Y-m-d H:i',
                    'data' =>new \DateTime($data[0]['fecha'])
                ))
                ->add("fechaEditEvent_final","datetime", array(
                    "required" => false,
                    "label" => "Fecha final",
                    'date_widget' => 'single_text',
                    'time_widget' => "single_text",
                    'format' => 'Y-m-d H:i',
                    'data' => is_null($data[0]['fecha_final']) ? null : new \DateTime($data[0]['fecha_final'])
                ))
                ->add("fechaEditEvent_publicacion","datetime", array(
                    "required" => false,
                    "label" => "Fecha de publicación",
                    'date_widget' => 'single_text',
                    'time_widget' => "single_text",
                    "empty_data" => null,
                    'data' =>is_null($data[0]['fecha_publicacion']) ? null : new \DateTime($data[0]['fecha_publicacion'])
                ))
                ->add("subscribe","checkbox", array(
                    "required" => false,
                    "label" => "¿Permitir suscripciones?",
                    'label_attr' => array(
                        'class' => 'block'
                    ),
                    'data' => (boolean)$data[0]['subscribe'],
                    "attr" => array(
                        "class" => "form-control",
                        'data-toggle' => 'toggle',
                        'data-on' => "Si",
                        'data-off' => "No",
                        'data-onstyle'=>"info"
                    )
                ))
                ->add("categories","choice", array(
                    "required" => true,
                    "label" => "Categoría",
                    "data" => $data[0]['idcategory'],
                    'choices' => $categories,
                    "attr" => array(
                        "class" => "form-control"
                    )
                ))
                ->add("latitud","text", array(
                    "required" => false,
                    "label" => "Localización - Latitud",
                    "data" => $data[0]['latitud'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba la latitud",
                        "class" => "form-control"
                    )
                ))
                ->add("longitud","text", array(
                    "required" => false,
                    "label" => "Localización - Longitud",
                    "data" => $data[0]['longitud'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba la longitud",
                        "class" => "form-control"
                    )
                ))
                ->add("link","text", array(
                    "required" => false,
                    "label" => "Link para información extra",
                    "data" => is_null($data[0]['link']) ? "http://" : $data[0]["link"],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Link para información extra",
                        "class" => "form-control"
                    )
                ))
                ->add("foto_principal","file", array(
                    "required" => false,
                    "label" => "Foto principal",
                    "empty_data" => null,
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Foto principal",
                        "class" => "file"
                    )
                ))
                ->add("foto_miniatura","file", array(
                    "required" => false,
                    "label" => "Foto miniatura",
                    "empty_data" => null,
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Foto miniatura",
                        "class" => "file"
                    )
                ))
                ->add('id','hidden',array(
                    'required' => true,
                    "data" => $data[0]['id']
                ))
                ->setMethod('POST');

            for($lang = 0;$lang<count($languages);$lang++){
                $form->add("titulo_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Título",
                    "data" => $data['edit'][$lang][0]["titulo"],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => " Título del evento",
                        "class" => "form-control"
                    )
                ))
                ->add("descripcion_{$languages[$lang]['idIdioma']}","textarea", array(
                    "required" => false,
                    "label" => "Descripción",
                    "data" => $data['edit'][$lang][0]['descripcion'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 1000,
                        "placeholder" => "Descripción del evento",
                        "class" => "form-control"
                    )
                ))
                ->add("organizacion_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Organización del evento",
                    "data" => $data['edit'][$lang][0]['organizacion'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba quién organiza este evento.",
                        "class" => "form-control"
                    )
                ))
                ->add("lugar_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Lugar del evento",
                    "data" => $data['edit'][$lang][0]['lugar'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 255,
                        "placeholder" => "Escriba el lugar donde se desarrollará el evento",
                        "class" => "form-control"
                    )
                ))
                ->add("url_{$languages[$lang]['idIdioma']}","text", array(
                    "required" => false,
                    "label" => "Video",
                    "data" => $data['edit'][$lang][0]['url'],
                    "trim" => true,
                    "attr" => array(
                        "maxlength" => 300,
                        "placeholder" => "Escriba la dirección de un video de youtube.",
                        "class" => "form-control"
                    )
                ));
            }

        }
        return $form->getForm();
    }

    /**
     * Método del controlador EventoController - inicializa el formulario de creación de nuevas categorías
     * @param array $languages
     * @param null $data
     * @return mixed
     */
    public function initNewCategoryForm($languages = array(),$data = null)
    {
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("icon","file", array(
                "required" => false,
                "label" => "Subir Icono",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "placeholder" => "Foto principal",
                    "class" => "file"
                )
            ))
            ->add("defaultMain","file", array(
                "required" => false,
                "label" => "Foto principal por defecto",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "placeholder" => "Foto principal por defecto",
                    "class" => "file"
                )
            ))
            ->add("defaultMini","file", array(
                "required" => false,
                "label" => "Foto miniatura por defecto",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "placeholder" => "Foto miniatura por defecto",
                    "class" => "file"
                )
            ))
            ->setMethod('POST');
        for($lang = 0;$lang<count($languages);$lang++){
            $form->add("category_{$languages[$lang]['idIdioma']}","text", array(
                "required" => false,
                "label" => "Categoría",
                "data" => isset($data['edit'][$lang][0]['name']) ? $data['edit'][$lang][0]['name'] : $data['edit'][$lang][0]['category'],
                "trim" => true,
                "attr" => array(
                    "placeholder" => "Nombre de la categoría",
                    "class" => "form-control"
                )
            ));
        }
        return $form->getForm();
    }

    /**
     * Método del controlador EventoController - inicializa el formulario de creación de nuevos pueblos ( para las comarcas )
     * @param null $data
     * @return mixed
     */
    public function initNewVillageForm($data = null){
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("name","text", array(
                "required" => false,
                "label" => "Nombre del pueblo",
                "data" => (string)$data[0]['name'],
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "placeholder" => "Escriba el nombre del pueblo",
                    "class" => "form-control"
                )
            ))
            ->setMethod('POST')
            ->getForm();
        return $form;
    }

    /**
     * Método del controlador EventoController - inicializa el formulario de busca de eventos
     * @return mixed
     */
    public function initSearchEvent(){
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("search1","date", array(
                "required" => false,
                "label" => "Buscar evento por día ",
                "label_attr" => array(
                    "class" => "margin-left-1"
                ),
                'widget' => 'single_text',
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "placeholder" => "Escriba la fecha",
                    "class" => "form-control"
                )
            ))
            ->add("search2","text", array(
                "required" => false,
                "label" => " Nombre del evento ",
                "label_attr" => array(
                    "class" => "margin-left-2"
                ),
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "placeholder" => "Escriba nombre del evento",
                    "class" => "form-control"
                )
            ))
            ->setMethod('POST')
            ->getForm();
        return $form;
    }

    /**
     * Método del controlador EventoController - controla la edición de datos de un evento.
     * @param $id
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createEditEvent($id,Request $request)
    {
        $firefox = false;
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
            $firefox = true;
        }
        $role = $this->getRole();
        $request->get('id');

        $userId = $this->getUser();
        $hasComarca = $this->hasComarca();

        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            //Cambiamos el id
            $userId = $father[0]['id'];
            $hasComarca = $father[0]['comarca'];
        }

        switch($request->getMethod()){
            case 'GET':
                return $this->indexAction("editEvent.twig",$id);
            case 'POST':
                $categoriesFilterByActive = $this->get('myapp.eventService')->getCategoriesActives($userId);

                //Formulario de insertar por idiomas
                $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($userId);

                if($role == "ROLE_USER" && $hasComarca){
                    $pueblos = $this->get('myapp.eventService')->getVillages($userId);
                }else{
                    if($hasComarca){
                        $villageId = $this->get('myapp.userProvider')->getSubVillages($userId);
                        $pueblos = $this->get('myapp.eventService')->getVillagesForSubuser($villageId[0]['villageId']);
                    }else{
                        $pueblos = null;
                    }
                }
                $pueblosToSelect = $this->getVillagesToDisplay($pueblos);

                $form = $this->initNewEditEventForm($userLanguagesInfo,null,$this->getCategoryToDisplay($categoriesFilterByActive),$pueblosToSelect,$hasComarca);
                $form->handleRequest($request);
                if($form->isValid())
                {
                    try{
                        $data = $form->getData();
                        if($firefox)
                        {
                            $parameters = $request->request->all();
                            $dateInicial = $parameters['form']['fechaEditEvent']['date'];
                            $timeInicial = $parameters['form']['fechaEditEvent']['time'];
                            $fechaInicial = date_create_from_format("d/m/Y H:i","$dateInicial $timeInicial");
                            $data['fechaEditEvent'] = $fechaInicial;

                            if(empty($parameters['form']['fechaEditEvent_final']['date']) || empty($parameters['form']['fechaEditEvent_final']['time'])){
                                $data['fechaEditEvent_final'] = null;
                            }else{
                                $dateFinal = $parameters['form']['fechaEditEvent_final']['date'];
                                $timeFinal = $parameters['form']['fechaEditEvent_final']['time'];
                                $fechaFinal = date_create_from_format("d/m/Y H:i","$dateFinal $timeFinal");
                                $data['fechaEditEvent_final'] = $fechaFinal;
                            }


                            if(!empty($parameters['form']['fechaEditEvent_publicacion']['date']) && !empty($parameters['form']['fechaEditEvent_publicacion']['time'])){
                                $datePublicacion = $parameters['form']['fechaEditEvent_publicacion']['date'];
                                $timePublicacion = $parameters['form']['fechaEditEvent_publicacion']['time'];
                                $fechaPublicacion = date_create_from_format("d/m/Y H:i","$datePublicacion $timePublicacion");
                                $data['fechaEditEvent_publicacion'] = $fechaPublicacion;
                            }

                        }else{

                            if(!$data['fechaEditEvent_final']){
                                $data['fechaEditEvent_final'] = null;
                            }

                        }
                        //Si fecha final < fecha inicial, ponemos fecha final = fecha inicial
                        if($data['fechaEditEvent_final']< $data['fechaEditEvent']){
                            $data['fechaEditEvent_final'] = null;
                        }

                        if($data['fechaEditEvent_publicacion'] && $data['fechaEditEvent_publicacion'] > $data['fechaEditEvent']){
                            $data['fechaEditEvent_publicacion'] = new \Datetime();
                        }



                        $mainPhoto = $data['foto_principal'];
                        $miniPhoto = $data['foto_miniatura'];
                        $eventId = $id;

                        //Save multi idioma, sacamos los datos que son traducciones a un array aparte
                        $unique = array(
                            0 => "programaFiestas",
                            1 => "subscribe",
                            2 => "pueblos",
                            3 => "fechaEditEvent",
                            4 => "fechaEditEvent_final",
                            5 => "fechaEditEvent_publicacion",
                            6 => "categories",
                            7 => "latitud",
                            8 => "longitud",
                            9 => "foto_principal",
                            10 => "foto_miniatura",
                            11 => "id",
                            12 => "link",
                        );
                        $dataToTranslate = $this->translateDataToSave($unique,$data,5);
                        if($data['link']=="http://"){
                            $data['link']=null;
                        }

                        //Editamos lo esencial
                        $this->get('myapp.eventService')->editEvent($data,$userId,$eventId);

                        //Editamos los restos
                        $this->manageTranslations("textos_eventos","idEvento",$dataToTranslate,$eventId);


                        if(!is_null($mainPhoto)){
                            if($mainPhoto->isValid()) {
                                $ext = $this->get('myapp.eventService')->getMainExt($userId,$eventId);
                                if(!$this->get('myapp.eventService')->isDefaultImgMain($userId,$eventId)){
                                    unlink($this->FILE_PATH.'/eventos/'.$userId.'/'.$eventId.'/main'.$eventId.'.'.$ext[0]['type']);
                                }
                                $this->get('myapp.eventService')->editMainPhoto($userId,$mainPhoto->getClientOriginalExtension(),$eventId);
                                $path = $this->FILE_PATH . 'eventos/'.$userId.'/'.$eventId.'/';
                                $newFileName = 'main'.$eventId .'.' . $mainPhoto->getClientOriginalExtension();
                                $mainPhoto->move($path, $newFileName);
                            }
                        }
                        if(!is_null($miniPhoto)){
                            if($miniPhoto->isValid()) {
                                $ext = $this->get('myapp.eventService')->getMiniExt($userId,$eventId);
                                if(!$this->get('myapp.eventService')->isDefaultImgMini($userId,$eventId)) {
                                    unlink($this->FILE_PATH . '/eventos/' . $userId . '/' . $eventId . '/mini' . $eventId . '.' . $ext[0]['type']);
                                }
                                $this->get('myapp.eventService')->editMiniPhoto($userId,$miniPhoto->getClientOriginalExtension(),$eventId);
                                $pathFile = $this->FILE_PATH . 'eventos/'.$userId.'/'.$eventId.'/';
                                $FileName = 'mini'.$eventId .'.' . $miniPhoto->getClientOriginalExtension();
                                $miniPhoto->move($pathFile, $FileName);
                            }
                        }
                        return $this->redirect('/eventos/');
                    }catch (\Exception $e)
                    {
                        return $this->json(array('status'=>$e->getMessage()));
                    }
                }
            break;
        }

    }

    /**
     * Método del controlador EventoController - Gestiona la creación de un nuevo evento en la bbdd.
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createNewEvent(Request $request){
        $role = $this->getRole();
        $id = $this->getUser();
        $hasComarca = $this->hasComarca();


        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($id);
            //Cambiamos el id
            $id = $father[0]['id'];
            $hasComarca = $father[0]['comarca'];
        }

        $currentLanguage = $this->get("myapp.idiomaService")->getCurrentLanguage($id);


        $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($id);
        switch($request->getMethod()){
            case 'GET':
                return $this->indexAction("evento.twig");
            case 'POST':


                $categoriesFilterByActive = $this->get('myapp.eventService')->getCategoriesActives($id);
                $pueblosToSelect = null;

                if($hasComarca){
                    if($role == "ROLE_USER"){
                        $pueblos = $this->get('myapp.eventService')->getVillages($id);
                    }else{
                        $villageId = $this->get('myapp.userProvider')->getSubVillages($id);
                        $pueblos = $this->get('myapp.eventService')->getVillagesForSubuser($villageId[0]['villageId']);
                    }
                    $pueblosToSelect = $this->getVillagesToDisplay($pueblos);
                }

                $form = $this->initNewEventForm($userLanguagesInfo,$this->getCategoryToDisplay($categoriesFilterByActive),$pueblosToSelect);
                $form->handleRequest($request);
                if($form->isValid()) {
                    try {
                        $data = $form->getData();
                        $duplicados = $data['copias'];

                            $firefox = false;
                            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
                                $firefox = true;
                            }

                            if ($firefox) {
                                $parameters = $request->request->all();
                                $dateInicial = $parameters['form']['fechaInicial']['date'];
                                $timeInicial = $parameters['form']['fechaInicial']['time'];
                                $fechaInicial = date_create_from_format("Y/m/d H:i", "$dateInicial $timeInicial");
                                $data['fechaInicial'] = $fechaInicial;

                                if (empty($parameters['form']['fechaFinal']['date']) && empty($parameters['form']['fechaFinal']['time'])) {
                                    $data['fechaFinal'] = null;
                                } else {
                                    $dateFinal = $parameters['form']['fechaFinal']['date'];
                                    $timeFinal = $parameters['form']['fechaFinal']['time'];
                                    $fechaFinal = date_create_from_format("Y/m/d H:i", "$dateFinal $timeFinal");
                                    $data['fechaFinal'] = $fechaFinal;
                                }

                                if (!empty($parameters['form']['fechaPublicacion']['date']) && !empty($parameters['form']['fechaPublicacion']['time'])) {
                                    $datePublicacion = $parameters['form']['fechaPublicacion']['date'];
                                    $timePublicacion = $parameters['form']['fechaPublicacion']['time'];
                                    $fechaPublicacion = date_create_from_format("Y/m/d H:i", "$datePublicacion $timePublicacion");
                                    $data['fechaPublicacion'] = $fechaPublicacion;

                                }

                            } else {

                                if (!$data['fechaFinal']) {
                                    $data['fechaFinal'] = null;
                                }

                            }
                            //Si fecha final < fecha inicial, ponemos fecha final = fecha inicial
                            if ($data['fechaFinal'] < $data['fechaInicial']) {
                                $data['fechaFinal'] = null;
                            }

                            if ($data['fechaPublicacion'] && $data['fechaPublicacion'] > $data['fechaInicial']) {
                                $data['fechaPublicacion'] = new \Datetime();
                            }
                        //ddd($duplicados);
                            for($i = 1; $i <= $duplicados; $i++) {
                                $mainPhoto = $data['foto_principal'];
                                $miniPhoto = $data['foto_miniatura'];

                                //PREPARAMOS LOS DATOS PARA GUARDARLOS
                                $unique = array(
                                    0 => "programaFiestas",
                                    1 => "pueblos",
                                    2 => "fechaInicial",
                                    3 => "fechaFinal",
                                    4 => "fechaPublicacion",
                                    5 => "categories",
                                    6 => "latitud",
                                    7 => "longitud",
                                    8 => "foto_principal",
                                    9 => "foto_miniatura",
                                    10 => "subscribe",
                                    11 => "link"
                                );
                                $dataToTranslate = $this->translateDataToSave($unique, $data, 5);
                                //EMPEZAMOS A GUARDAR EL EVENTO

                                if($data['link']=="http://"){
                                    $data['link']=null;
                                }

                                $EventId = $this->get('myapp.eventService')->saveEvent($data, $id);

                                //AHORA LAS TRADUCCIONES
                                $this->manageTranslations("textos_eventos", "idEvento", $dataToTranslate, $EventId);


                                //TESTEO DE ERRORES-------------------------------------
                                if (!is_null($mainPhoto)) {
                                    if ($mainPhoto->isValid()) {
                                        $this->get('myapp.eventService')->saveMainPhoto($id, $mainPhoto->getClientOriginalExtension(), $EventId, false);
                                        $path = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                        $newFileName = 'main' . $EventId . '.' . $mainPhoto->getClientOriginalExtension();
                                        $mainPhoto->move($path, $newFileName);

                                    }
                                } else {
                                    $ext = "jpg";
                                    $category = $this->get('myapp.eventService')->getCategoryById($data['categories']);

                                    if (!empty($currentLanguage)) {
                                        $category = $this->getTextos($currentLanguage[0]['idIdioma'], $category, 'idCategory', 'textos_category');
                                    }

                                    $path = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                    $newFileName = 'main' . $EventId . '.jpg';

                                    $dstfile = $path . '/' . $newFileName;
                                    if (!file_exists($path)) {
                                        mkdir($path, 0775, true);
                                    }

                                    $path = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                    $srcfile = $this->FILE_PATH . 'eventos/' . $id . '/default/';

                                    if (file_exists($srcfile)) {
                                        $directories = scandir($srcfile);
                                        $copied = false;
                                        for ($a = 0; $a < count($directories); $a++) {
                                            if ($directories[$a] !== "." && $directories[$a] !== ".." && strstr($directories[$a], strtolower(str_replace(' ', '_', $category[0]['category'])) . ".")) {
                                                $ext = pathinfo($srcfile . $directories[$a], PATHINFO_EXTENSION);
                                                $dstfile = $path . '/main' . $EventId . '.' . $ext;
                                                $srcfile = $this->FILE_PATH . 'eventos/' . $id . '/default/' . strtolower(str_replace(' ', '_', $category[0]['category'])) . '.' . $ext;
                                                copy($srcfile, $dstfile);
                                                $copied = true;
                                                break;
                                            }
                                        }

                                        if (!$copied) {
                                            $srcfile = $this->FILE_PATH . 'eventos/default/' . strtolower(str_replace(' ', '_', $category[0]['category'])) . '.jpg';
                                            if (file_exists($srcfile)) {
                                                copy($srcfile, $dstfile);
                                            } else {
                                                $srcfile = $this->FILE_PATH . 'eventos/default/general.jpg';
                                                if (file_exists($srcfile)) {
                                                    copy($srcfile, $dstfile);
                                                }
                                            }
                                        }
                                    } else {
                                        $srcfile = $this->FILE_PATH . 'eventos/default/' . strtolower(str_replace(' ', '_', $category[0]['category'])) . '.jpg';
                                        if (file_exists($srcfile)) {
                                            copy($srcfile, $dstfile);
                                        } else {
                                            $srcfile = $this->FILE_PATH . 'eventos/default/general.jpg';
                                            if (file_exists($srcfile)) {
                                                copy($srcfile, $dstfile);
                                            }
                                        }
                                    }
                                    $this->get('myapp.eventService')->saveMainPhoto($id, $ext, $EventId, true);
                                }


                                if (!is_null($miniPhoto)) {
                                    if ($miniPhoto->isValid()) {
                                        $this->get('myapp.eventService')->saveMiniPhoto($id, $miniPhoto->getClientOriginalExtension(), $EventId, false);
                                        $path = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                        $newFileName = 'mini' . $EventId . '.' . $miniPhoto->getClientOriginalExtension();
                                        $miniPhoto->move($path, $newFileName);
                                    }
                                } else {
                                    $ext = "jpg";
                                    $category = $this->get('myapp.eventService')->getCategoryById($data['categories']);

                                    if (!empty($currentLanguage)) {
                                        $category = $this->getTextos($currentLanguage[0]['idIdioma'], $category, 'idCategory', 'textos_category');
                                    }

                                    $path = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                    $newFileName = 'mini' . $EventId . '.jpg';

                                    $dstfile = $path . '/' . $newFileName;
                                    if (!file_exists($path)) {
                                        mkdir($path, 0775, true);
                                    }

                                    $path = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                    $srcfile = $this->FILE_PATH . 'eventos/' . $id . '/default/';
                                    if (file_exists($srcfile)) {
                                        $copied = false;
                                        $directories = scandir($srcfile);
                                        for ($a = 0; $a < count($directories); $a++) {
                                            if ($directories[$a] !== "." && $directories[$a] !== ".." && strstr($directories[$a], strtolower(str_replace(' ', '_', $category[0]['category'])) . "1.")) {
                                                $ext = pathinfo($srcfile . $directories[$a], PATHINFO_EXTENSION);
                                                $dstfile = $path . '/mini' . $EventId . '.' . $ext;
                                                $srcfile = $this->FILE_PATH . 'eventos/' . $id . '/default/' . strtolower(str_replace(' ', '_', $category[0]['category'])) . '1.' . $ext;
                                                copy($srcfile, $dstfile);
                                                $copied = true;
                                                break;
                                            }
                                        }

                                        if (!$copied) {
                                            $srcfile = $this->FILE_PATH . 'eventos/default/' . strtolower(str_replace(' ', '_', $category[0]['category'])) . '1.jpg';
                                            if (file_exists($srcfile)) {
                                                copy($srcfile, $dstfile);
                                            } else {
                                                $srcfile = $this->FILE_PATH . 'eventos/default/general1.jpg';
                                                if (file_exists($srcfile)) {
                                                    copy($srcfile, $dstfile);
                                                }
                                            }
                                        }
                                    } else {
                                        $srcfile = $this->FILE_PATH . 'eventos/default/' . strtolower(str_replace(' ', '_', $category[0]['category'])) . '1.jpg';
                                        if (file_exists($srcfile)) {
                                            copy($srcfile, $dstfile);
                                        } else {
                                            $srcfile = $this->FILE_PATH . 'eventos/default/general1.jpg';
                                            if (file_exists($srcfile)) {
                                                copy($srcfile, $dstfile);
                                            }
                                        }
                                    }

                                    $this->get('myapp.eventService')->saveMiniPhoto($id, $ext, $EventId, true);
                                }
                                //Si es una de las copias que se vuelvan a subir las imagenes con el nuevo id
                                if($i>1){

                                    if (!is_null($mainPhoto)){
                                        $lastEventId =$this->get('myapp.eventService')->getEventId($id)['0']['id']-1;
                                        $this->get('myapp.eventService')->saveMainPhoto($id, $mainPhoto->getClientOriginalExtension(), $EventId, false);

                                        $pathOrigen = $this->FILE_PATH . 'eventos/' . $id . '/' . $lastEventId;
                                        $FileNameOrigen = $pathOrigen.'/main' . $lastEventId . '.' . $mainPhoto->getClientOriginalExtension();

                                        $pathDestino = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                        $FileNameDestino = $pathDestino.'/main' . $EventId . '.' . $mainPhoto->getClientOriginalExtension();

                                        if (!file_exists($pathDestino)) {
                                            mkdir($pathDestino, 0777, true);
                                        }
                                        copy($FileNameOrigen, $FileNameDestino);
                                    }
                                    if (!is_null($miniPhoto)) {
                                        $lastEventId =$this->get('myapp.eventService')->getEventId($id)['0']['id']-1;
                                        $this->get('myapp.eventService')->saveMiniPhoto($id, $miniPhoto->getClientOriginalExtension(), $EventId, false);

                                        $pathOrigen = $this->FILE_PATH . 'eventos/' . $id . '/' . $lastEventId;
                                        $FileNameOrigen = $pathOrigen.'/mini' . $lastEventId . '.' . $mainPhoto->getClientOriginalExtension();

                                        $pathDestino = $this->FILE_PATH . 'eventos/' . $id . '/' . $EventId;
                                        $FileNameDestino = $pathDestino.'/mini' . $EventId . '.' . $mainPhoto->getClientOriginalExtension();

                                        if (!file_exists($pathDestino)) {
                                            mkdir($pathDestino, 0777, true);
                                        }
                                        copy($FileNameOrigen, $FileNameDestino);


                                    }

                                }
                            }
                        return $this->redirect('/eventos/');
                        }
                    catch
                        (\Exception $e)
                    {
                        return $this->json(array('status' => $e->getMessage()));
                    }
                }else{
                        return $this->json(array('status' => "invalido"));
                     }

            break;
        }
    }

    /**
     * Método del controlador EventoController - gestiona la creación en la bbdd de una nueva categoría.
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createCategory(Request $request){
        $id = $this->getUser();

        $role = $this->getRole();
        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($id);
            //Cambiamos el id
            $id = $father[0]['id'];
        }

        //Formulario de insertar por idiomas
        $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($id);

        switch($request->getMethod()){
            case 'GET':
                return $this->indexAction("evento.twig");
            case 'POST':
                $form = $this->initNewCategoryForm($userLanguagesInfo);
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    try
                    {
                        $data = $form->getData();

                        $uploads=$data['icon'];

                        //Save multi idioma, sacamos los datos que son traducciones a un array aparte
                        $unique = array(
                            0 => "icon",
                            1 => "defaultMain",
                            2 => "defaultMini",
                        );
                        $dataToTranslate = $this->translateDataToSave($unique,$data,1);

                        //Guardamos los datos esenciales
                        $lastCategoryId = $this->get('myapp.eventService')->saveCategory(null, $id,is_null($uploads) ? "jpg" : $uploads->getClientOriginalExtension());

                        //Guardamos las traducciones
                        $this->manageTranslations("textos_category","idCategory",$dataToTranslate,$lastCategoryId);


                        if(!is_null($uploads)){
                            $path=$this->FILE_PATH.'category/'.$id;
                            $newFileName=$lastCategoryId.'.'. $uploads->getClientOriginalExtension();
                            $uploads->move($path,$newFileName );
                        }
                        else{
                            $path = $this->FILE_PATH . 'category/'.$id;
                            $srcfile = 'images/etno.jpg';
                            $dstfile =  $path.'/'.$lastCategoryId.'.jpg';
                            if (!file_exists($path)) {
                                mkdir($path, 0775, true);
                                // return $this->json("Error en el save");
                            }
                            copy($srcfile,$dstfile);
                        }


                        return $this->redirect('/eventos/');
                    }
                    catch(\FileException $e)
                    {
                        return $this->json(array('status'=>$e->getMessage()));
                    }
                    catch(\Exception $exception)
                    {
                        return $this->json(array('status'=>$exception->getMessage()));
                    }
                }
                else
                {
                    return $this->json(array( "status"=>"not valid"));
                }
            break;
        }
    }

    /**
     * Método del controlador EventoController - gestiona la creación de un nuevo pueblo en la bbdd.
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createVillage(Request $request){

        $userId = $this->getUser();

        $role = $this->getRole();
        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            //Cambiamos el id
            $userId = $father[0]['id'];
        }

        switch($request->getMethod()){
            case 'GET':
                return $this->indexAction("evento.twig");
            case 'POST':
                $form = $this->initNewVillageForm();
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    try
                    {
                        $data = $form->getData();
                        $this->get('myapp.eventService')->saveVillage($data['name'],$userId);
                        return $this->redirect('/eventos/');
                    }
                    catch(\FileException $e)
                    {
                        return $this->json(array('status'=>$e->getMessage()));
                    }
                    catch(\Exception $exception)
                    {
                        return $this->json(array('status'=>$exception->getMessage()));
                    }
                }
                else
                {
                    return $this->json(array( "status"=>"not valid"));
                }
            break;
        }
    }

    /**
     * Método del controlador EventoController - desactiva un evento de un usuario.
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function DeactiveEvent($id,Request $request){
        $this->get('myapp.eventService')->deactive($id);
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - activamos el evento
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ActiveEvent($id,Request $request){
        $this->get('myapp.eventService')->active($id);
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - borramos un evento y sus imagenes
     * @param $id
     * @param $from
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function DeleteEvent($id,$from,Request $request){
        $userId = $this->getUser();

        $role = $this->getRole();
        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            //Cambiamos el id
            $userId = $father[0]['id'];
        }

        $this->rrmdir($this->FILE_PATH . 'eventos/'.$userId.'/'.$id);
        $this->get('myapp.eventService')->delete($id);
        return $this->redirect('/'.$from.'/');
    }


    /**
     * Método del controlador EventoController - Borrado masivo de eventos.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function DeleteMassiveEvent(Request $request){
        $userId = $this->getUser();
        $role = $this->getRole();
        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            //Cambiamos el id
            $userId = $father[0]['id'];
        }

        $checks = (array)$request->get('check');
        $ids = (array)$request->get('id');

        for($u = 0 ;$u<count($checks);$u++){
            if($checks[$u] =="1"){
                $this->rrmdir($this->FILE_PATH . 'eventos/'.$userId.'/'.$ids[$u]);
                $this->get('myapp.eventService')->delete($ids[$u]);
            }
        }
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - Edición de categorias de eventos
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editCategory($id,Request $request){
        $userId = $this->getUser();

        $role = $this->getRole();
        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            //Cambiamos el id
            $userId = $father[0]['id'];
        }

        $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($userId);
        switch($request->getMethod()) {
            //CASE GET : Mostrar vista
            case 'GET':
                $path = $this->getPath();
                $capabilities = $this->get_User()->getCapabilities();
                $userSurname = $this->get('myapp.userProvider')->getUserSurname($userId);
                $appIconData = $this->getAppIconData();
                $category = $this->get('myapp.eventService')->getCatById($id,$userId);
                //Tiene el nombre de la categoria en el campo name, necesito que se llame category, no puedo modificar la vista para no
                // romper la api
                foreach($category[0] as $kcat => $vcat){
                    if($kcat == "name"){
                        $category[0]['category'] = $vcat;
                        unset($category[0]['name']);
                    }
                }

                $currentLanguage = $this->get("myapp.idiomaService")->getCurrentLanguage($userId);
                if(!empty($currentLanguage)){
                    $category = $this->getTextos($currentLanguage[0]['idIdioma'],$category,'idCategory','textos_category');
                    //Como mostramos todos los idiomas a la vez necesito sacarlos todos
                    $categoryTranslation = array();
                    foreach($userLanguagesInfo as $key => $v){
                        $categoryTranslation[] = $this->getTextos($v['idIdioma'],$category,'idCategory','textos_category');
                    }
                    $category['edit'] = $categoryTranslation;
                }

                $form = $this->initNewCategoryForm($userLanguagesInfo,$category);
                $numincidencias = $this->getIncidenciasNoChecked($userId);
                $imgMainDetected = false;
                $imgMiniDetected = false;

                //Checkeamos si existe una carpeta del usuario llamada default, si existe, chekeamos si existe el file
                $pathDefaultMain = $this->FILE_PATH . 'eventos/'.$userId.'/default';
                if(file_exists($pathDefaultMain)){
                    $directories = scandir($pathDefaultMain);
                    for($a = 0;$a<count($directories);$a++){
                        if($directories[$a] !== "." && $directories[$a]!== ".." && strstr($directories[$a],strtolower(str_replace(' ', '_', $category[0]['category'])).".")){
                            $imagenPorDefectoMain = $path.'files/eventos/'.$userId.'/default/'.$directories[$a];
                            $imgMainDetected = true;
                            break;
                        }
                    }
                    if(!$imgMainDetected){
                        $pathDefaultMain = $this->FILE_PATH.'eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'.jpg';
                        if(file_exists($pathDefaultMain)){
                            $imagenPorDefectoMain = $path.'files/eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'.jpg';
                        }else{
                            $pathDefaultMain = $this->FILE_PATH.'eventos/default/general.jpg';
                            if(file_exists($pathDefaultMain)){
                                $imagenPorDefectoMain = $path.'files/eventos/default/general.jpg';
                            }
                        }
                    }
                }else{
                    $pathDefaultMain = $this->FILE_PATH.'eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'.jpg';
                    if(file_exists($pathDefaultMain)){
                        $imagenPorDefectoMain = $path.'files/eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'.jpg';
                    }else{
                        $pathDefaultMain = $this->FILE_PATH.'eventos/default/general.jpg';
                        if(file_exists($pathDefaultMain)){
                            $imagenPorDefectoMain = $path.'files/eventos/default/general.jpg';
                        }
                    }
                }

                //Ahora el mini
                //Checkeamos si existe una carpeta del usuario llamada default, si existe, chekeamos si existe el file
                $pathDefaultMini = $this->FILE_PATH . 'eventos/'.$userId.'/default';
                if(file_exists($pathDefaultMini)){
                    $directories = scandir($pathDefaultMini);
                    for($a = 0;$a<count($directories);$a++){
                        if($directories[$a] !== "." && $directories[$a]!== ".." && strstr($directories[$a],strtolower(str_replace(' ', '_', $category[0]['category']))."1.")){
                            $imagenPorDefectoMini = $path.'files/eventos/'.$userId.'/default/'.$directories[$a];
                            $imgMiniDetected = true;
                            break;
                        }

                    }

                    if(!$imgMiniDetected){
                        $pathDefaultMini = $this->FILE_PATH.'eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'1.jpg';
                        if(file_exists($pathDefaultMini)){
                            $imagenPorDefectoMini = $path.'files/eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'1.jpg';
                        }else{
                            $pathDefaultMini = $this->FILE_PATH.'eventos/default/general1.jpg';
                            if(file_exists($pathDefaultMini)){
                                $imagenPorDefectoMini = $path.'files/eventos/default/general1.jpg';
                            }
                        }
                    }
                }else{
                    $pathDefaultMini = $this->FILE_PATH.'eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'1.jpg';
                    if(file_exists($pathDefaultMini)){
                        $imagenPorDefectoMini = $path.'files/eventos/default/'.strtolower(str_replace(' ', '_', $category[0]['category'])).'1.jpg';
                    }else{
                        $pathDefaultMini = $this->FILE_PATH.'eventos/default/general1.jpg';
                        if(file_exists($pathDefaultMini)){
                            $imagenPorDefectoMini = $path.'files/eventos/default/general1.jpg';
                        }
                    }
                }

                return  $this->loadMainTemplate('editEventCategory.twig',array(
                    'editCategory_form' =>$form->createView(),
                    'path' => $path,
                    'usuario' => $userSurname,
                    'capabilities' => $capabilities,
                    'data' => $appIconData,
                    'category' => $category,
                    'numincidencias' => $numincidencias,
                    "imagenPorDefectoMain" => $imagenPorDefectoMain,
                    "imagenPorDefectoMini" => $imagenPorDefectoMini,
                    'idiomas' => $userLanguagesInfo
                ));
            //CASE POST: editar categoría, redireccionar a eventos
            case 'POST':
                $form = $this->initNewCategoryForm($userLanguagesInfo);
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    try
                    {
                        //Recibimos los datos del form edit, si imagen es null, no intentamos guardarla, en caso contrario si.
                        $data = $form->getData();

                        $uploads=$data['icon'];
                        if(!is_null($uploads)){
                            if($uploads->isValid()) {
                                $unique = array(
                                    0 => "icon",
                                    1 => "defaultMain",
                                    2 => "defaultMini",
                                );
                                $dataToTranslate = $this->translateDataToSave($unique,$data,1);

                                $this->get('myapp.eventService')->updateCategory($id,null,$uploads->getClientOriginalExtension());

                                //Editamos los restos
                                $this->manageTranslations("textos_category","idCategory",$dataToTranslate,$id);

                                $path=$this->FILE_PATH.'category/'.$userId;
                                $newFileName=$id . '.' . $uploads->getClientOriginalExtension();
                                $uploads->move($path,$newFileName);
                            }
                        }else{

                            //Save multi idioma, sacamos los datos que son traducciones a un array aparte
                            $unique = array(
                                0 => "icon",
                                1 => "defaultMain",
                                2 => "defaultMini",
                            );
                            $dataToTranslate = $this->translateDataToSave($unique,$data,1);

                            //Guardamos lo esencial
                            $this->get('myapp.eventService')->updateCategory($id,null,null);

                            //Editamos los restos
                            $this->manageTranslations("textos_category","idCategory",$dataToTranslate,$id);

                        }


                        //Al introducir el multi-idioma tengo que chequear el nombre de la categoria para los siguientes casos
                        // Solo usamos el españól, si por alguna razon no existe no se cambian las imagenes
                        if(isset($data['category_1']) && !is_null($data['category_1'])){
                            $categoryName = $data['category_1'];

                            if(!is_null($data['defaultMain'])){
                                //Movemos la imagen a la carpeta de por defecto del usuario en la sección de eventos, el nombre será el de la misma categoría normales
                                if($data['defaultMain']->isValid()) {
                                    $path=$this->FILE_PATH.'eventos/'.$userId.'/default';
                                    $newFileName=strtolower(str_replace(' ', '_',$categoryName)).'.' . $data['defaultMain']->getClientOriginalExtension();
                                    //Si ya tiene una imagen hay que borrarla porque no controlo las extensiones
                                    if(file_exists($path)){
                                        $directories = scandir($path);
                                        for($a = 0;$a<count($directories);$a++){
                                            if($directories[$a] !== "." && $directories[$a]!== ".." && strstr($directories[$a],strtolower(str_replace(' ', '_', $categoryName)).".")){
                                                unlink($path.'/'.$directories[$a]);
                                            }
                                        }
                                    }
                                    $data['defaultMain']->move($path,$newFileName);
                                }
                            }

                            if(!is_null($data['defaultMini'])){
                                ////Movemos la imagen a la carpeta de por defecto del usuario
                                if($data['defaultMini']->isValid()) {
                                    $path=$this->FILE_PATH.'eventos/'.$userId.'/default';
                                    $newFileName=strtolower(str_replace(' ', '_', $categoryName)).'1.' . $data['defaultMini']->getClientOriginalExtension();
                                    //Si ya tiene una imagen hay que borrarla porque no controlo las extensiones
                                    if(file_exists($path)){
                                        $directories = scandir($path);
                                        for($a = 0;$a<count($directories);$a++){
                                            if($directories[$a] !== "." && $directories[$a]!== ".." && strstr($directories[$a],strtolower(str_replace(' ', '_', $categoryName))."1.")){
                                                unlink($path.'/'.$directories[$a]);
                                            }
                                        }
                                    }
                                    $data['defaultMini']->move($path,$newFileName);
                                }
                            }

                        }

                        return $this->redirect('/eventos/');
                    }
                    catch(\FileException $e)
                    {
                        return $this->json(array('status'=>$e->getMessage()));
                    }
                    catch(\Exception $exception)
                    {
                        return $this->json(array('status'=>$exception->getMessage()));
                    }
                }
                else
                {
                    return $this->json(array( "status"=>"not valid"));
                }
            break;
        }
    }

    /**
     * Método del controlador EventoController - Desactivamos una categoría
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function DeactiveCategory($id){
        $this->get('myapp.eventService')->deactiveCategory($id);
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - Activamos una categoría
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ActiveCategory($id){
        $this->get('myapp.eventService')->activeCategory($id);
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - Borramos una categoría
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function DeleteCategory($id){
        $userId = $this->getUser();
        $role = $this->getRole();
        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            //Cambiamos el id
            $userId = $father[0]['id'];
        }

        $category = $this->get('myapp.eventService')->getCatById($id,$userId);
        //Tiene el nombre de la categoria en el campo name, necesito que se llame category, no puedo modificar la vista para no
        // romper la api
        foreach($category[0] as $kcat => $vcat){
            if($kcat == "name"){
                $category[0]['category'] = $vcat;
                unset($category[0]['name']);
            }
        }

        $userLanguagesInfo =$this->get("myapp.idiomaService")->getUserLanguagesInfo($userId);
        $currentLanguage = $this->get("myapp.idiomaService")->getCurrentLanguage($userId);
        if(!empty($currentLanguage)){
            $category = $this->getTextos($currentLanguage[0]['idIdioma'],$category,'idCategory','textos_category');
            //Como mostramos todos los idiomas a la vez necesito sacarlos todos
            $categoryTranslation = array();
            foreach($userLanguagesInfo as $key => $v){
                $categoryTranslation[] = $this->getTextos($v['idIdioma'],$category,'idCategory','textos_category');
            }
            $category['edit'] = $categoryTranslation;
        }


        //Borramos la imagen de la categoria
        if(file_exists($this->FILE_PATH.'/category/'.$userId.'/'.$id.'.'.$category[0]['fileType'])){
            unlink($this->FILE_PATH.'/category/'.$userId.'/'.$id.'.'.$category[0]['fileType']);
        }

        //Si tiene asociada una carpeta de imagenes por defecto, borramos las imagenes de esta categoría.
        if(file_exists($this->FILE_PATH.'/eventos/'.$userId.'/default/')){
            $dirs = scandir($this->FILE_PATH.'/eventos/'.$userId.'/default/');
            for($a = 0;$a<count($dirs);$a++){
                if($dirs[$a] !== "." && $dirs[$a]!== ".." && strstr($dirs[$a],strtolower(str_replace(' ', '_', $category[0]['category'])))){
                    unlink($this->FILE_PATH.'/eventos/'.$userId.'/default/'.$dirs[$a]);
                }

            }
        }

        try{
            $this->get('myapp.eventService')->deleteCategory($id);
        }catch (\Exception $e){
            return $this->json(false);
        }

        return $this->json(true);
        //return $this->redirect('/eventos/');

    }

    /**
     * Método del controlador EventoController - Editamos los datos de un pueblo ( Vista + Lógica )
     * @param $id
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editVillage($id,Request $request){
        $userId = $this->getUser();

        $role = $this->getRole();
        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            //Cambiamos el id
            $userId = $father[0]['id'];
        }

        switch($request->getMethod()){
            //CASE GET : mostrar vista editVillage
            case 'GET':
                $path = $this->getPath();
                $capabilities = $this->get_User()->getCapabilities();
                $userSurname = $this->get('myapp.userProvider')->getUserSurname($userId);
                $appIconData = $this->getAppIconData();
                $village = $this->get("myapp.eventService")->getVillageById($id,$userId);
                $form = $this->initNewVillageForm($village);
                $numincidencias = $this->getIncidenciasNoChecked($userId);
                $eventos='eventos';
                return  $this->loadMainTemplate('editVillage.twig',array(
                    'editVillageForm' =>$form->createView(),
                    'path' => $path,
                    'usuario' => $userSurname,
                    'capabilities' => $capabilities,
                    'data' => $appIconData,
                    'village' => $village,
                    'numincidencias' => $numincidencias,
                    'eventos'=>$eventos
                ));
            //CASE POST: editar pueblo y redireccionar a eventos
            case 'POST':
                $form = $this->initNewVillageForm();
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    try
                    {
                        $data = $form->getData();
                        $this->get('myapp.eventService')->editVillage($data['name'],$userId,$id);
                        return $this->redirect('/eventos/');
                    }
                    catch(\FileException $e)
                    {
                        return $this->json(array('status'=>$e->getMessage()));
                    }
                    catch(\Exception $exception)
                    {
                        return $this->json(array('status'=>$exception->getMessage()));
                    }
                }
                else
                {
                    return $this->json(array( "status"=>"not valid"));
                }
            break;
        }
    }

    /**
     * Método del controlador EventoController - Desactivamos un pueblo
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactiveVillage($id,Request $request){
        $this->get('myapp.eventService')->deactiveVillage($id);
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - Activamos un pueblo
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activeVillage($id,Request $request){
        $this->get('myapp.eventService')->activeVillage($id);
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - Borramos un pueblo
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteVillage($id,Request $request){
        $this->get('myapp.eventService')->deleteVillage($id);
        return $this->redirect('/eventos/');
    }

    /**
     * Método del controlador EventoController - Filtramos los eventos por nombre y fecha.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function filterEvents(Request $request){

        $date = $request->get('date');
        $name = $request->get('name');

        $hasComarca = $this->hasComarca();
        $role = $this->getRole();
        $userId = $this->getUser();

        if($role == "ROLE_SUBUSER"){
            $father = $this->get("myapp.userProvider")->getParentDataBySubUserId($userId);
            $userId = $father[0]['id'];
            $hasComarca = $father[0]['comarca'];
        }

        $currentLanguage =$this->get("myapp.idiomaService")->getCurrentLanguage($userId);
        $data = "";


        if(!empty($date) && !empty($name)){
            //Filtramos por nombre y fecha
            $d = date_create_from_format('d/m/Y', $date);
            if(!$d){
                $d = date_create_from_format('Y-m-d', $date);
            }

            $eventos = $this->get('myapp.eventService')->filterEvents($d->format('Y-m-d'),$name);

            if(!empty($currentLanguage)){
                $eventos = $this->getTextos($currentLanguage[0]['idIdioma'],$eventos,'idEvento','textos_eventos');
            }

            $eventos = $this->insertCategoryEvent($eventos);


            $data = $this->renderView("paneles.twig",array(
                'hasComarca' => $hasComarca,
                "events" => $eventos,
                "path" => $this->getPath(),
                'dia' => $d->format('d-m-Y'),
                'name' => $name
            ));

        }else{
            if(!empty($date)){
                //Filtramos por fecha
                $d = date_create_from_format('d/m/Y', $date);
                if(!$d){
                    $d = date_create_from_format('Y-m-d', $date);
                }
                $eventos = $this->get('myapp.eventService')->filterEvents($d->format('Y-m-d'));
                if(!empty($currentLanguage)){
                    $eventos = $this->getTextos($currentLanguage[0]['idIdioma'],$eventos,'idEvento','textos_eventos');
                }
                $eventos = $this->insertCategoryEvent($eventos);
                $data = $this->renderView("paneles.twig",array(
                    'hasComarca' => $hasComarca,
                    "events" => $eventos,
                    "path" => $this->getPath(),
                    'dia' => $d->format('d-m-Y'),
                    'name' => null
                ));

            }elseif(!empty($name)){
                //Filtramos por nombre
                $eventos = $this->get('myapp.eventService')->filterEvents(null,$name);

                if(!empty($currentLanguage)){
                    $eventos = $this->getTextos($currentLanguage[0]['idIdioma'],$eventos,'idEvento','textos_eventos');
                }

                $eventos = $this->insertCategoryEvent($eventos);
                $data = $this->renderView("paneles.twig",array(
                    'hasComarca' => $hasComarca,
                    "events" => $eventos,
                    "path" => $this->getPath(),
                    'dia' => null,
                    'name' => $name
                ));
            }else{
                //Si llega aqui es porque ambos están vacios, data vale null
                $eventos = $this->get('myapp.eventService')->filterEvents(null,null);

                if(!empty($currentLanguage)){
                    $eventos = $this->getTextos($currentLanguage[0]['idIdioma'],$eventos,'idEvento','textos_eventos');
                }
                $eventos = $this->insertCategoryEvent($eventos);
                $data = $this->renderView("paneles.twig",array(
                    'hasComarca' => $hasComarca,
                    "events" => $eventos,
                    "path" => $this->getPath(),
                    'dia' => null,
                    'name' => null
                ));
            }
        }
        return $this->json($data);
    }

  /*  public function filterEventsName(Request $request){
        $name = $request->get('name');
        $eventos = $this->get('myapp.eventService')->filterEventsName($name);
        $data = $this->renderView("paneles.twig",array(
            'hasComarca' => $this->hasComarca(),
            "events" => $eventos,
            "path" => $this->getPath(),
            'dia' => null,
            'name' => $name
        ));

        return $this->json($data);
    } */

    /**
     * Método del controlador EventoController - Inicializamos el formulario con las acciones en lote de los eventos.
     * @param $accionesLote
     * @return mixed
     */
    public function initNewAccionesLoteForm($accionesLote)
    {
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("accionesLote","choice", array(
                "required" => true,
                "label" => false,
                'choices' => $accionesLote,
                "attr" => array(
                    "class" => "form-control"
                )
            ))
            ->setMethod('POST')
            ->getForm();
        return $form;
    }

    /**
     * Método del controlador EventoController - Montamos el array para el select de aciones en lote.
     * @param $getAcciones
     * @return array
     */
    private function accionsToSelect($getAcciones){
        $acciones=array();
        foreach($getAcciones as $valor){
            $acciones[(int)$valor['id']] =  (string)$valor['nombre'];
        }
        return $acciones;
    }

    /**
     * Método del controlador EventoController - Chequeamos si es un evento activo
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkActiveEventAction($id,Request $request){
        return $this->json((boolean)$this->get("myapp.eventService")->checkEventActive($id));
    }

    /**
     * Método del controlador EventoController - Generamos las suscripciones de eventos ( es una llamada de la api )
     * @param $eventId
     * @param Request $request
     * @return Response
     */
    public function makeSubscribeAction($eventId,Request $request){
        $name = $request->get("name");
        $email1 = $request->get("email");
        $phone = $request->get("phone");
        $response = null;
        //Chequeamos que el evento tiene subscribe = 1
        if($this->get("myapp.eventService")->hasSubscribe($eventId)){
            $this->get("myapp.eventService")->insertSubscribe($eventId,$name,$email1,$phone);
            $eventoData = $this->get("myapp.eventService")->getEventById($eventId);
            $currentLanguage =$this->get("myapp.idiomaService")->getCurrentLanguage($eventoData[0]['usuario']);
            //enviamos correo
            $email = is_null($this->get("myapp.userProvider")->getSubscriptionEmail($eventoData[0]['usuario'])) ? $this->get("myapp.userProvider")->getEmail($eventoData[0]['usuario']) : $this->get("myapp.userProvider")->getSubscriptionEmail($eventoData[0]['usuario']);
            $titulo = empty($eventoData[0]['titulo']) ? $this->get("myapp.eventService")->getEventTituloTextosById($eventId,$currentLanguage[0]['idIdioma']) : $eventoData[0]['titulo'];
            $msn1 = "Se ha producido una suscripción con los siguientes datos, Nombre: {$name}.  Email: {$email1}. Teléfono: {$phone} Al evento {$eventId}, con titulo: {$titulo}.";
            $subject = "Nueva suscripción a un evento";

            $this->get("myapp.emailService")->sendCustomEmail($email,$msn1,$subject);
            return new Response(200);
        }else{
            return new Response(403);
        }
    }

    /**
     * Método del controlador EventoController - borramos la imagen mini
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function DeleteEventImagenMini($id){

        $userId = $this->getUser();
        $type= $this->get('myapp.eventService')->geteventofilemini($id);
        $path = $this->FILE_PATH . 'eventos/'.$userId.'/'.$id.'/';
        $newFileName = 'mini' . $id . '.'. $type[0]['type'];

        $dstfile = $path . '/' . $newFileName;
        $srcfile = $this->FILE_PATH . 'eventos/default/general.jpg';
        if (file_exists($srcfile)) {
            copy($srcfile, $dstfile);
        }

        return $this->redirect('/eventos/edit/'.$id.'/');
    }

    /**
     * Método del controlador EventoController - borramos la imagen main
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function DeleteEventImagenMain($id){

        $userId = $this->getUser();
        $type= $this->get('myapp.eventService')->geteventofilemain($id);
        $path = $this->FILE_PATH . 'eventos/'.$userId.'/'.$id.'/';

        $newFileName = 'main' . $id . '.'. $type[0]['type'];

        $dstfile = $path . '/' . $newFileName;
        $srcfile = $this->FILE_PATH . 'eventos/default/general.jpg';
        if (file_exists($srcfile)) {
            copy($srcfile, $dstfile);
        }

        return $this->redirect('/eventos/edit/'.$id.'/');
    }

}