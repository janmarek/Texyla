<?php //netteloader=ArgumentOutOfRangeException

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 */

class
ArgumentOutOfRangeException
extends
InvalidArgumentException{}class
InvalidStateException
extends
RuntimeException{function
__construct($message='',$code=0,Exception$previous=NULL){if(version_compare(PHP_VERSION,'5.3','<')){$this->previous=$previous;parent::__construct($message,$code);}else{parent::__construct($message,$code,$previous);}}}class
NotImplementedException
extends
LogicException{}class
NotSupportedException
extends
LogicException{}class
DeprecatedException
extends
NotSupportedException{}class
MemberAccessException
extends
LogicException{}class
IOException
extends
RuntimeException{}class
FileNotFoundException
extends
IOException{}class
DirectoryNotFoundException
extends
IOException{}class
FatalErrorException
extends
Exception{private$severity;public
function
__construct($message,$code,$severity,$file,$line,$context){parent::__construct($message,$code);$this->severity=$severity;$this->file=$file;$this->line=$line;$this->context=$context;}public
function
getSeverity(){return$this->severity;}}final
class
Annotations{static
private$cache=array();final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
has(Reflector$r,$name){$cache=&self::init($r);return!empty($cache[$name]);}public
static
function
get(Reflector$r,$name){$cache=&self::init($r);return
isset($cache[$name])?end($cache[$name]):NULL;}public
static
function
getAll(Reflector$r,$name=NULL){$cache=&self::init($r);if($name===NULL){return$cache;}elseif(isset($cache[$name])){return$cache[$name];}else{return
array();}}public
static
function&init(Reflector$r){$cache=&self::$cache[$r->getName()];if($r
instanceof
ReflectionClass){$cache=&$cache[''];}elseif($r
instanceof
ReflectionMethod){$cache=&$cache[$r->getDeclaringClass()->getName()];}else{$cache=&$cache['$'.$r->getDeclaringClass()->getName()];}if($cache!==NULL){return$cache;}preg_match_all('#@([a-zA-Z0-9_]+)(?:\(((?>[^\'")]+|\'[^\']*\'|"[^"]*")*)\))?#',$r->getDocComment(),$matches,PREG_SET_ORDER);$cache=array();foreach($matches
as$match){if(isset($match[2])){preg_match_all('#[,\s](?>([a-zA-Z0-9_]+)\s*=\s*)?([^\'",\s][^,]*|\'[^\']*\'|"[^"]*")#',','.$match[2],$matches,PREG_SET_ORDER);$items=array();$key='';$val=TRUE;foreach($matches
as$m){list(,$key,$val)=$m;if($val[0]==="'"||$val[0]==='"'){$val=substr($val,1,-1);}elseif(strcasecmp($val,'true')===0){$val=TRUE;}elseif(strcasecmp($val,'false')===0){$val=FALSE;}elseif(strcasecmp($val,'null')===0){$val=NULL;}elseif(is_numeric($val)){$val=1*$val;}if($key===''){$items[]=$val;}else{$items[$key]=$val;}}$items=count($items)<2&&$key===''?$val:new
ArrayObject($items,ArrayObject::ARRAY_AS_PROPS);}else{$items=TRUE;}$cache[$match[1]][]=$items;}return$cache;}}final
class
ArrayTools{final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
get(array$arr,$key,$default=NULL){foreach(is_array($key)?$key:array($key)as$k){if(is_array($arr)&&array_key_exists($k,$arr)){$arr=$arr[$k];}else{return$default;}}return$arr;}public
static
function&getRef(&$arr,$key){foreach(is_array($key)?$key:array($key)as$k){if(is_array($arr)||$arr===NULL){$arr=&$arr[$k];}else{throw
new
InvalidArgumentException('Traversed item is not an array.');}}return$arr;}public
static
function
mergeTree($arr1,$arr2){$res=$arr1+$arr2;foreach(array_intersect_key($arr1,$arr2)as$k=>$v){if(is_array($v)&&is_array($arr2[$k])){$res[$k]=self::mergeTree($v,$arr2[$k]);}}return$res;}public
static
function
searchKey($arr,$key){$foo=array($key=>NULL);return
array_search(key($foo),array_keys($arr),TRUE);}public
static
function
insertBefore(array&$arr,$key,array$inserted){$offset=self::searchKey($arr,$key);$arr=array_slice($arr,0,$offset,TRUE)+$inserted+array_slice($arr,$offset,NULL,TRUE);}public
static
function
insertAfter(array&$arr,$key,array$inserted){$offset=self::searchKey($arr,$key);$offset=$offset===FALSE?NULL:$offset+1;$arr=array_slice($arr,0,$offset,TRUE)+$inserted+array_slice($arr,$offset,NULL,TRUE);}}interface
IComponent{const
NAME_SEPARATOR='-';function
getName();function
getParent();function
setParent(IComponentContainer$parent=NULL,$name=NULL);}if(version_compare(PHP_VERSION,'5.2.0','<')){throw
new
Exception('Nette Framework requires PHP 5.2.0 or newer.');}@set_magic_quotes_runtime(FALSE);if(version_compare(PHP_VERSION,'5.2.2','<')){function
fixCallback(&$callback){if(is_object($callback)){$callback=array($callback,'__invoke');return;}if(is_string($callback)&&strpos($callback,':')){$callback=explode('::',$callback);}if(is_array($callback)&&is_string($callback[0])&&$a=strrpos($callback[0],'\\')){$callback[0]=substr($callback[0],$a+1);}}}else{function
fixCallback(&$callback){if(is_object($callback)){$callback=array($callback,'__invoke');}elseif(is_string($callback)&&$a=strrpos($callback,'\\')){$callback=substr($callback,$a+1);}elseif(is_array($callback)&&is_string($callback[0])&&$a=strrpos($callback[0],'\\')){$callback[0]=substr($callback[0],$a+1);}}}function
fixNamespace(&$class){if($a=strrpos($class,'\\')){$class=substr($class,$a+1);}}final
class
ObjectMixin{private
static$extMethods;private
static$methods;final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
call($_this,$name,$args){$class=get_class($_this);if($name===''){throw
new
MemberAccessException("Call to class '$class' method without name.");}if(preg_match('#^on[A-Z]#',$name)){$rp=new
ReflectionProperty($class,$name);if($rp->isPublic()&&!$rp->isStatic()){$list=$_this->$name;if(is_array($list)||$list
instanceof
Traversable){foreach($list
as$handler){fixCallback($handler);if(!is_callable($handler)){$able=is_callable($handler,TRUE,$textual);throw
new
InvalidStateException("Event handler '$textual' is not ".($able?'callable.':'valid PHP callback.'));}call_user_func_array($handler,$args);}}return
NULL;}}if($cb=self::extensionMethod($class,$name)){array_unshift($args,$_this);return
call_user_func_array($cb,$args);}throw
new
MemberAccessException("Call to undefined method $class::$name().");}public
static
function
extensionMethod($class,$name,$callback=NULL){if(self::$extMethods===NULL||$name===NULL){$list=get_defined_functions();foreach($list['user']as$fce){$pair=explode('_prototype_',$fce);if(count($pair)===2){self::$extMethods[$pair[1]][$pair[0]]=$fce;self::$extMethods[$pair[1]]['']=NULL;}}if($name===NULL)return
NULL;}$class=strtolower($class);$l=&self::$extMethods[strtolower($name)];if($callback!==NULL){fixCallback($callback);if(!is_callable($callback)){$able=is_callable($callback,TRUE,$textual);throw
new
InvalidArgumentException("Extension method handler '$textual' is not ".($able?'callable.':'valid PHP callback.'));}$l[$class]=$callback;$l['']=NULL;return
NULL;}if(empty($l)){return
FALSE;}elseif(isset($l[''][$class])){return$l[''][$class];}$cl=$class;do{$cl=strtolower($cl);if(isset($l[$cl])){return$l[''][$class]=$l[$cl];}}while(($cl=get_parent_class($cl))!==FALSE);foreach(class_implements($class)as$cl){$cl=strtolower($cl);if(isset($l[$cl])){return$l[''][$class]=$l[$cl];}}return$l[''][$class]=FALSE;}public
static
function&get($_this,$name){$class=get_class($_this);if($name===''){throw
new
MemberAccessException("Cannot read a class '$class' property without name.");}if(!isset(self::$methods[$class])){self::$methods[$class]=array_flip(get_class_methods($class));}$name[0]=$name[0]&"\xDF";$m='get'.$name;if(isset(self::$methods[$class][$m])){$val=$_this->$m();return$val;}$m='is'.$name;if(isset(self::$methods[$class][$m])){$val=$_this->$m();return$val;}$name=func_get_arg(1);throw
new
MemberAccessException("Cannot read an undeclared property $class::\$$name.");}public
static
function
set($_this,$name,$value){$class=get_class($_this);if($name===''){throw
new
MemberAccessException("Cannot assign to a class '$class' property without name.");}if(!isset(self::$methods[$class])){self::$methods[$class]=array_flip(get_class_methods($class));}$name[0]=$name[0]&"\xDF";if(isset(self::$methods[$class]['get'.$name])||isset(self::$methods[$class]['is'.$name])){$m='set'.$name;if(isset(self::$methods[$class][$m])){$_this->$m($value);return;}else{$name=func_get_arg(1);throw
new
MemberAccessException("Cannot assign to a read-only property $class::\$$name.");}}$name=func_get_arg(1);throw
new
MemberAccessException("Cannot assign to an undeclared property $class::\$$name.");}public
static
function
has($_this,$name){if($name===''){return
FALSE;}$class=get_class($_this);if(!isset(self::$methods[$class])){self::$methods[$class]=array_flip(get_class_methods($class));}$name[0]=$name[0]&"\xDF";return
isset(self::$methods[$class]['get'.$name])||isset(self::$methods[$class]['is'.$name]);}}abstract
class
Object{final
public
function
getClass(){return
get_class($this);}final
public
function
getReflection(){return
new
ReflectionObject($this);}public
function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}public
static
function
__callStatic($name,$args){$class=get_called_class();throw
new
MemberAccessException("Call to undefined static method $class::$name().");}public
static
function
extensionMethod($name,$callback=NULL){if(strpos($name,'::')===FALSE){$class=get_called_class();}else{list($class,$name)=explode('::',$name);}return
ObjectMixin::extensionMethod($class,$name,$callback);}public
function&__get($name){return
ObjectMixin::get($this,$name);}public
function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}public
function
__isset($name){return
ObjectMixin::has($this,$name);}public
function
__unset($name){throw
new
MemberAccessException("Cannot unset the property $this->class::\$$name.");}}abstract
class
Component
extends
Object
implements
IComponent{private$parent;private$name;private$monitors=array();public
function
__construct(IComponentContainer$parent=NULL,$name=NULL){if($parent!==NULL){$parent->addComponent($this,$name);}elseif(is_string($name)){$this->name=$name;}}public
function
lookup($type,$need=TRUE){fixNamespace($type);if(!isset($this->monitors[$type])){$obj=$this->parent;$path=self::NAME_SEPARATOR.$this->name;$depth=1;while($obj!==NULL){if($obj
instanceof$type)break;$path=self::NAME_SEPARATOR.$obj->getName().$path;$depth++;$obj=$obj->getParent();if($obj===$this)$obj=NULL;}if($obj){$this->monitors[$type]=array($obj,$depth,substr($path,1),FALSE);}else{$this->monitors[$type]=array(NULL,NULL,NULL,FALSE);}}if($need&&$this->monitors[$type][0]===NULL){throw
new
InvalidStateException("Component '$this->name' is not attached to '$type'.");}return$this->monitors[$type][0];}public
function
lookupPath($type,$need=TRUE){fixNamespace($type);$this->lookup($type,$need);return$this->monitors[$type][2];}public
function
monitor($type){fixNamespace($type);if(empty($this->monitors[$type][3])){if($obj=$this->lookup($type,FALSE)){$this->attached($obj);}$this->monitors[$type][3]=TRUE;}}public
function
unmonitor($type){fixNamespace($type);unset($this->monitors[$type]);}protected
function
attached($obj){}protected
function
detached($obj){}final
public
function
getName(){return$this->name;}final
public
function
getParent(){return$this->parent;}public
function
setParent(IComponentContainer$parent=NULL,$name=NULL){if($parent===NULL&&$this->parent===NULL&&$name!==NULL){$this->name=$name;return$this;}elseif($parent===$this->parent&&$name===NULL){return$this;}if($this->parent!==NULL&&$parent!==NULL){throw
new
InvalidStateException("Component '$this->name' already has a parent.");}if($parent===NULL){if($this->parent->getComponent($this->name,FALSE)===$this){throw
new
InvalidStateException("The current parent still recognizes component '$this->name' as its child.");}$this->refreshMonitors(0);$this->parent=NULL;}else{if($parent->getComponent($name,FALSE)!==$this){throw
new
InvalidStateException("The given parent does not recognize component '$name' as its child.");}$this->validateParent($parent);$this->parent=$parent;if($name!==NULL)$this->name=$name;$tmp=array();$this->refreshMonitors(0,$tmp);}return$this;}protected
function
validateParent(IComponentContainer$parent){}private
function
refreshMonitors($depth,&$missing=NULL,&$listeners=array()){if($this
instanceof
IComponentContainer){foreach($this->getComponents()as$component){if($component
instanceof
Component){$component->refreshMonitors($depth+1,$missing,$listeners);}}}if($missing===NULL){foreach($this->monitors
as$type=>$rec){if(isset($rec[1])&&$rec[1]>$depth){if($rec[3]){$this->monitors[$type]=array(NULL,NULL,NULL,TRUE);$listeners[]=array($this,$rec[0]);}else{unset($this->monitors[$type]);}}}}else{foreach($this->monitors
as$type=>$rec){if(isset($rec[0])){continue;}elseif(!$rec[3]){unset($this->monitors[$type]);}elseif(isset($missing[$type])){$this->monitors[$type]=array(NULL,NULL,NULL,TRUE);}else{$this->monitors[$type]=NULL;if($obj=$this->lookup($type,FALSE)){$listeners[]=array($this,$obj);}else{$missing[$type]=TRUE;}$this->monitors[$type][3]=TRUE;}}}if($depth===0){$method=$missing===NULL?'detached':'attached';foreach($listeners
as$item){$item[0]->$method($item[1]);}}}public
function
__clone(){if($this->parent===NULL){return;}elseif($this->parent
instanceof
ComponentContainer){$this->parent=$this->parent->_isCloning();if($this->parent===NULL){$this->refreshMonitors(0);}}else{$this->parent=NULL;$this->refreshMonitors(0);}}final
public
function
__wakeup(){throw
new
NotImplementedException;}}interface
IComponentContainer
extends
IComponent{function
addComponent(IComponent$component,$name);function
removeComponent(IComponent$component);function
getComponent($name);function
getComponents($deep=FALSE,$filterType=NULL);}class
ComponentContainer
extends
Component
implements
IComponentContainer{private$components=array();private$cloning;public
function
addComponent(IComponent$component,$name,$insertBefore=NULL){if($name===NULL){$name=$component->getName();}if(is_int($name)){$name=(string)$name;}elseif(!is_string($name)){throw
new
InvalidArgumentException("Component name must be integer or string, ".gettype($name)." given.");}elseif(!preg_match('#^[a-zA-Z0-9_]+$#',$name)){throw
new
InvalidArgumentException("Component name must be non-empty alphanumeric string, '$name' given.");}if(isset($this->components[$name])){throw
new
InvalidStateException("Component with name '$name' already exists.");}$obj=$this;do{if($obj===$component){throw
new
InvalidStateException("Circular reference detected while adding component '$name'.");}$obj=$obj->getParent();}while($obj!==NULL);$this->validateChildComponent($component);try{if(isset($this->components[$insertBefore])){$tmp=array();foreach($this->components
as$k=>$v){if($k===$insertBefore)$tmp[$name]=$component;$tmp[$k]=$v;}$this->components=$tmp;}else{$this->components[$name]=$component;}$component->setParent($this,$name);}catch(Exception$e){unset($this->components[$name]);throw$e;}}public
function
removeComponent(IComponent$component){$name=$component->getName();if(!isset($this->components[$name])||$this->components[$name]!==$component){throw
new
InvalidArgumentException("Component named '$name' is not located in this container.");}unset($this->components[$name]);$component->setParent(NULL);}final
public
function
getComponent($name,$need=TRUE){if(is_int($name)){$name=(string)$name;}elseif(!is_string($name)){throw
new
InvalidArgumentException("Component name must be integer or string, ".gettype($name)." given.");}else{$a=strpos($name,self::NAME_SEPARATOR);if($a!==FALSE){$ext=(string)substr($name,$a+1);$name=substr($name,0,$a);}if($name===''){throw
new
InvalidArgumentException("Component or subcomponent name must not be empty string.");}}if(!isset($this->components[$name])){$this->createComponent($name);}if(isset($this->components[$name])){if(!isset($ext)){return$this->components[$name];}elseif($this->components[$name]instanceof
IComponentContainer){return$this->components[$name]->getComponent($ext,$need);}elseif($need){throw
new
InvalidArgumentException("Component with name '$name' is not container and cannot have '$ext' component.");}}elseif($need){throw
new
InvalidArgumentException("Component with name '$name' does not exist.");}}protected
function
createComponent($name){$ucname=ucfirst($name);$method='createComponent'.$ucname;if($ucname!==$name&&method_exists($this,$method)&&$this->getReflection()->getMethod($method)->getName()===$method){$component=$this->$method($name);if($component
instanceof
IComponent&&$component->getParent()===NULL){$this->addComponent($component,$name);}}}final
public
function
getComponents($deep=FALSE,$filterType=NULL){$iterator=new
RecursiveComponentIterator($this->components);if($deep){$deep=$deep>0?RecursiveIteratorIterator::SELF_FIRST:RecursiveIteratorIterator::CHILD_FIRST;$iterator=new
RecursiveIteratorIterator($iterator,$deep);}if($filterType){fixNamespace($filterType);$iterator=new
InstanceFilterIterator($iterator,$filterType);}return$iterator;}protected
function
validateChildComponent(IComponent$child){}public
function
__clone(){if($this->components){$oldMyself=reset($this->components)->getParent();$oldMyself->cloning=$this;foreach($this->components
as$name=>$component){$this->components[$name]=clone$component;}$oldMyself->cloning=NULL;}parent::__clone();}public
function
_isCloning(){return$this->cloning;}}class
RecursiveComponentIterator
extends
RecursiveArrayIterator
implements
Countable{public
function
hasChildren(){return$this->current()instanceof
IComponentContainer;}public
function
getChildren(){return$this->current()->getComponents();}public
function
count(){return
iterator_count($this);}}class
Configurator
extends
Object{public$defaultConfigFile='%appDir%/config.ini';public$defaultServices=array('Nette\Application\Application'=>'Nette\Application\Application','Nette\Web\IHttpRequest'=>'Nette\Web\HttpRequest','Nette\Web\IHttpResponse'=>'Nette\Web\HttpResponse','Nette\Web\IUser'=>'Nette\Web\User','Nette\Caching\ICacheStorage'=>array(__CLASS__,'createCacheStorage'),'Nette\Web\Session'=>'Nette\Web\Session','Nette\Loaders\RobotLoader'=>array(__CLASS__,'createRobotLoader'));public
function
detect($name){switch($name){case'environment':if($this->detect('console')){return
Environment::CONSOLE;}else{return
Environment::getMode('production')?Environment::PRODUCTION:Environment::DEVELOPMENT;}case'production':if(PHP_SAPI==='cli'){return
FALSE;}elseif(isset($_SERVER['SERVER_ADDR'])||isset($_SERVER['LOCAL_ADDR'])){$addr=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR'];$oct=explode('.',$addr);return$addr!=='::1'&&(count($oct)!==4||($oct[0]!=='10'&&$oct[0]!=='127'&&($oct[0]!=='172'||$oct[1]<16||$oct[1]>31)&&($oct[0]!=='169'||$oct[1]!=='254')&&($oct[0]!=='192'||$oct[1]!=='168')));}else{return
TRUE;}case'console':return
PHP_SAPI==='cli';default:return
NULL;}}public
function
loadConfig($file){$name=Environment::getName();if($file
instanceof
Config){$config=$file;$file=NULL;}else{if($file===NULL){$file=$this->defaultConfigFile;}$file=Environment::expand($file);$config=Config::fromFile($file,$name,0);}if($config->variable
instanceof
Config){foreach($config->variable
as$key=>$value){Environment::setVariable($key,$value);}}$config->expand();$runServices=array();$locator=Environment::getServiceLocator();if($config->service
instanceof
Config){foreach($config->service
as$key=>$value){$key=strtr($key,'-','\\');if(is_string($value)){$locator->addService($key,$value);}else{if($value->factory){$locator->addService($key,$value->factory,isset($value->singleton)?$value->singleton:TRUE,(array)$value->option);}if($value->run){$runServices[]=$key;}}}}if(!$config->php){$config->php=$config->set;unset($config->set);}if($config->php
instanceof
Config){if(PATH_SEPARATOR!==';'&&isset($config->php->include_path)){$config->php->include_path=str_replace(';',PATH_SEPARATOR,$config->php->include_path);}foreach($config->php
as$key=>$value){if($value
instanceof
Config){unset($config->php->$key);foreach($value
as$k=>$v){$config->php->{"$key.$k"}=$v;}}}foreach($config->php
as$key=>$value){$key=strtr($key,'-','.');if(!is_scalar($value)){throw
new
InvalidStateException("Configuration value for directive '$key' is not scalar.");}if(function_exists('ini_set')){ini_set($key,$value);}else{switch($key){case'include_path':set_include_path($value);break;case'iconv.internal_encoding':iconv_set_encoding('internal_encoding',$value);break;case'mbstring.internal_encoding':mb_internal_encoding($value);break;case'date.timezone':date_default_timezone_set($value);break;case'error_reporting':error_reporting($value);break;case'ignore_user_abort':ignore_user_abort($value);break;case'max_execution_time':set_time_limit($value);break;default:if(ini_get($key)!=$value){throw
new
NotSupportedException('Required function ini_set() is disabled.');}}}}}if($config->const
instanceof
Config){foreach($config->const
as$key=>$value){define($key,$value);}}if(isset($config->mode)){foreach($config->mode
as$mode=>$state){Environment::setMode($mode,$state);}}foreach($runServices
as$name){$locator->getService($name);}$config->freeze();return$config;}public
function
createServiceLocator(){$locator=new
ServiceLocator;foreach($this->defaultServices
as$name=>$service){$locator->addService($name,$service);}return$locator;}public
static
function
createCacheStorage(){return
new
FileStorage(Environment::getVariable('tempDir'));}public
static
function
createRobotLoader($options){$loader=new
RobotLoader;$loader->autoRebuild=!Environment::isProduction();$dirs=isset($options['directory'])?$options['directory']:array(Environment::getVariable('appDir'),Environment::getVariable('libsDir'));$loader->addDirectory($dirs);$loader->register();return$loader;}}final
class
Framework{const
NAME='Nette Framework';const
VERSION='0.9.2';const
REVISION='b9fd602 released on 2009-11-12';const
PACKAGE='PHP 5.2';final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
compareVersion($version){return
version_compare($version,self::VERSION);}public
static
function
promo($xhtml=TRUE){echo'<a href="http://nettephp.com/" title="Nette Framework - The Most Innovative PHP Framework"><img ','src="http://nettephp.com/images/nette-powered.gif" alt="Powered by Nette Framework" width="80" height="15"',($xhtml?' />':'>'),'</a>';}}final
class
Debug{public
static$productionMode;public
static$consoleMode;public
static$time;private
static$firebugDetected;private
static$ajaxDetected;private
static$consoleData;public
static$maxDepth=3;public
static$maxLen=150;public
static$showLocation=FALSE;const
DEVELOPMENT=FALSE;const
PRODUCTION=TRUE;const
DETECT=NULL;public
static$strictMode=FALSE;public
static$onFatalError=array();public
static$mailer=array(__CLASS__,'defaultMailer');public
static$emailSnooze=172800;private
static$enabled=FALSE;private
static$logFile;private
static$logHandle;private
static$sendEmails;private
static$emailHeaders=array('To'=>'','From'=>'noreply@%host%','X-Mailer'=>'Nette Framework','Subject'=>'PHP: An error occurred on the server %host%','Body'=>'[%date%] %message%');private
static$colophons=array(array(__CLASS__,'getDefaultColophons'));private
static$enabledProfiler=FALSE;public
static$counters=array();const
LOG='LOG';const
INFO='INFO';const
WARN='WARN';const
ERROR='ERROR';const
TRACE='TRACE';const
EXCEPTION='EXCEPTION';const
GROUP_START='GROUP_START';const
GROUP_END='GROUP_END';final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
_init(){self::$time=microtime(TRUE);self::$consoleMode=PHP_SAPI==='cli';self::$productionMode=self::DETECT;self::$firebugDetected=isset($_SERVER['HTTP_USER_AGENT'])&&strpos($_SERVER['HTTP_USER_AGENT'],'FirePHP/');self::$ajaxDetected=isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';register_shutdown_function(array(__CLASS__,'_shutdownHandler'));}public
static
function
_shutdownHandler(){static$types=array(E_ERROR=>1,E_CORE_ERROR=>1,E_COMPILE_ERROR=>1,E_PARSE=>1);$error=error_get_last();if(self::$enabled&&isset($types[$error['type']])){if(!headers_sent()){header('HTTP/1.1 500 Internal Server Error');}if(ini_get('html_errors')){$error['message']=html_entity_decode(strip_tags($error['message']));}self::processException(new
FatalErrorException($error['message'],0,$error['type'],$error['file'],$error['line'],NULL),TRUE);}if(self::$productionMode){return;}foreach(headers_list()as$header){if(strncasecmp($header,'Content-Type:',13)===0){if(substr($header,14,9)==='text/html'){break;}return;}}if(self::$enabledProfiler){if(self::$firebugDetected){self::fireLog('Nette profiler',self::GROUP_START);foreach(self::$colophons
as$callback){foreach((array)call_user_func($callback,'profiler')as$line)self::fireLog(strip_tags($line));}self::fireLog(NULL,self::GROUP_END);}if(!self::$ajaxDetected){$colophons=self::$colophons;?>

<style type="text/css">
/* <![CDATA[ */
	#netteProfilerContainer {
		position: fixed;
		_position: absolute;
		right: 5px;
		bottom: 5px;
		z-index: 23178;
	}

	#netteProfiler {
		font: normal normal 11px/1.4 Consolas, Arial;
		position: relative;
		padding: 3px;
		color: black;
		background: #EEE;
		border: 1px dotted gray;
		cursor: move;
		opacity: .70;
		=filter: alpha(opacity=70);
	}

	#netteProfiler * {
		color: inherit;
		background: inherit;
		text-align: inherit;
	}

	#netteProfilerIcon {
		position: absolute;
		right: 0;
		top: 0;
		line-height: 1;
		padding: 2px;
		color: black;
		text-decoration: none;
	}

	#netteProfiler:hover {
		opacity: 1;
		=filter: none;
	}

	#netteProfiler ul {
		margin: 0;
		padding: 0;
		width: 350px;
	}

	#netteProfiler li {
		margin: 0;
		padding: 1px;
		text-align: left;
		list-style: none;
	}

	#netteProfiler span[title] {
		border-bottom: 1px dotted gray;
		cursor: help;
	}

	#netteProfiler strong {
		color: red;
	}
/* ]]> */
</style>


<div id="netteProfilerContainer">
<div id="netteProfiler">
	<a id="netteProfilerIcon" href="#"><abbr>&#x25bc;</abbr></a
	><ul>
	<?php foreach($colophons
as$callback):?>
	<?php foreach((array)call_user_func($callback,'profiler')as$line):?><li><?php echo$line,"\n"?></li><?php endforeach?>
	<?php endforeach?>
	</ul>
</div>
</div>


<script type="text/javascript">
/* <![CDATA[ */
document.getElementById('netteProfiler').onmousedown = function(e) {
	e = e || event;
	this.posX = parseInt(this.style.left + '0');
	this.posY = parseInt(this.style.top + '0');
	this.mouseX = e.clientX;
	this.mouseY = e.clientY;

	var thisObj = this;

	document.documentElement.onmousemove = function(e) {
		e = e || event;
		thisObj.style.left = (e.clientX - thisObj.mouseX + thisObj.posX) + "px";
		thisObj.style.top = (e.clientY - thisObj.mouseY + thisObj.posY) + "px";
		return false;
	};

	document.documentElement.onmouseup = function(e) {
		document.documentElement.onmousemove = null;
		document.documentElement.onmouseup = null;
		document.cookie = 'netteProfilerPosition=' + thisObj.style.left + ':' + thisObj.style.top + '; path=/';
		return false;
	};
};

document.getElementById('netteProfilerIcon').onclick = function(e) {
	var arrow = this.getElementsByTagName('abbr')[0];
	var panel = this.nextSibling;
	var collapsed = panel.currentStyle ? panel.currentStyle.display == 'none' : getComputedStyle(panel, null).display == 'none';

	arrow.innerHTML = collapsed ? String.fromCharCode(0x25bc) : 'Profiler ' + String.fromCharCode(0x25ba);
	panel.style.display = collapsed ? 'block' : 'none';
	arrow.parentNode.style.position = collapsed ? 'absolute' : 'static';

	document.cookie = 'netteProfilerVisible=' + collapsed*1 + '; path=/';
	return false;
}

document.body.appendChild(document.getElementById('netteProfilerContainer'));

if (document.cookie.indexOf('netteProfilerVisible=0') > -1) {
	document.getElementById('netteProfilerIcon').onclick();
}

var _nettePos = document.cookie.match(/netteProfilerPosition=([0-9-]+px):([0-9-]+px)/);
if (_nettePos) {
	document.getElementById('netteProfiler').style.left = _nettePos[1];
	document.getElementById('netteProfiler').style.top = _nettePos[2];
}
/* ]]> */
</script>
<?php }}if(self::$consoleData){$payload=self::$consoleData;if(!function_exists('_netteDumpCb2')){function
_netteDumpCb2($m){return"$m[1]<a href='#' onclick='return !netteToggle(this)'>$m[2]($m[3]) ".($m[3]<7?'<abbr>&#x25bc;</abbr> </a><code>':'<abbr>&#x25ba;</abbr> </a><code class="collapsed">');}}ob_start();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex,noarchive">
	<meta name="generator" content="Nette Framework">

	<title>Nette Debug Console</title>

	<style type="text/css">
	/* <![CDATA[ */
		body {
			margin: 0;
			padding: 0;
			font: 9pt/1.5 Verdana, sans-serif;
			background: white;
			color: #333;
		}

		h1 {
			font-size: 13pt;
			margin: 0;
			padding: 2px 8px;
			background: black;
			color: white;
			border-bottom: 1px solid black;
		}

		h2 {
			font: 11pt/1.5 sans-serif;
			margin: 0;
			padding: 2px 8px;
			background: #3484d2;
			color: white;
		}

		a {
			text-decoration: none;
			color: #4197E3;
		}

		a abbr {
			font-family: sans-serif;
			color: #999;
		}

		p {
			margin: .8em 0
		}

		pre, code, table {
			font: 9pt/1.5 Consolas, monospace;
		}

		pre, table {
			background: #fffbcc;
			padding: .4em .7em;
			border: 1px dotted silver;
		}

		table pre {
			padding: 0;
			margin: 0;
			border: none;
		}

		pre.dump span {
			color: #c16549;
		}

		pre.dump a {
			color: #333;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		td, th {
			vertical-align: top;
			text-align: left;
			border: 1px solid #eeeebb;
		}

		th {
			width: 10;
			padding: 2px 3px 2px 8px;
			font-weight: bold;
		}

		td {
			padding: 2px 8px 2px 3px;
		}

		.odd, .odd pre {
			background: #faf5c3;
		}

	/* ]]> */
	</style>


	<script type="text/javascript">
	/* <![CDATA[ */
		document.write('<style> .collapsed { display: none; } <\/style>');

		function netteToggle(link, panelId)
		{
			var arrow = link.getElementsByTagName('abbr')[0];
			var panel = panelId ? document.getElementById(panelId) : link.nextSibling;
			while (panel.nodeType !== 1) panel = panel.nextSibling;
			var collapsed = panel.currentStyle ? panel.currentStyle.display == 'none' : getComputedStyle(panel, null).display == 'none';

			arrow.innerHTML = String.fromCharCode(collapsed ? 0x25bc : 0x25ba);
			panel.style.display = collapsed ? (panel.tagName.toLowerCase() === 'code' ? 'inline' : 'block') : 'none';

			return true;
		}
	/* ]]> */
	</script>
</head>



<body>
	<h1>Nette Debug Console</h1>
</body>
</html>
<?php $document=ob_get_clean()?>

<?php ob_start()?>
<?php foreach($payload
as$item):?>
	<?php if($item['title']):?>
	<h2><?php echo
htmlspecialchars($item['title'])?></h2>
	<?php endif?>

	<table>
	<?php $i=0?>
	<?php foreach((is_array($item['var'])?$item['var']:array(''=>$item['var']))as$key=>$val):?>
	<tr class="<?php echo$i++%
2?'odd':'even'?>">
		<th><?php echo
htmlspecialchars($key)?></th>
		<td><?php echo
preg_replace_callback('#(<pre class="dump">|\s+)?(.*)\((\d+)\) <code>#','_netteDumpCb2',Debug::dump($val,TRUE))?></td>
	</tr>
	<?php endforeach?>
	</table>
<?php endforeach?>
<?php $body=ob_get_clean()?>

<script type="text/javascript">
/* <![CDATA[ */
if (typeof _netteConsole === 'undefined') {
	_netteConsole = window.open('','_netteConsole','width=700,height=700,resizable,scrollbars=yes');
	_netteConsole.document.write(<?php echo
json_encode(preg_replace('#[ \t\r\n]+#',' ',$document))?>);
	_netteConsole.document.close();
	_netteConsole.document.onkeyup = function(e) {
		e = e || _netteConsole.event;
		if (e.keyCode == 27) _netteConsole.close();
	}
	_netteConsole.document.body.focus();
}
_netteConsole.document.body.innerHTML = _netteConsole.document.body.innerHTML + <?php echo
json_encode($body)?>;
/* ]]> */
</script>
<?php }}public
static
function
dump($var,$return=FALSE){if(!$return&&self::$productionMode){return$var;}$output="<pre class=\"dump\">".self::_dump($var,0)."</pre>\n";if(self::$showLocation){$trace=debug_backtrace();if(isset($trace[0]['file'],$trace[0]['line'])){$output=substr_replace($output,' <small>'.htmlspecialchars("in file {$trace[0]['file']} on line {$trace[0]['line']}",ENT_NOQUOTES).'</small>',-8,0);}}if(self::$consoleMode){$output=htmlspecialchars_decode(strip_tags($output),ENT_NOQUOTES);}if($return){return$output;}else{echo$output;return$var;}}public
static
function
consoleDump($var,$title=NULL){if(!self::$productionMode){self::$consoleData[]=array('title'=>$title,'var'=>$var);}return$var;}private
static
function
_dump(&$var,$level){static$tableUtf,$tableBin,$re='#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{10FFFF}]#u';if($tableUtf===NULL){foreach(range("\x00","\xFF")as$ch){if(ord($ch)<32&&strpos("\r\n\t",$ch)===FALSE)$tableUtf[$ch]=$tableBin[$ch]='\\x'.str_pad(dechex(ord($ch)),2,'0',STR_PAD_LEFT);elseif(ord($ch)<127)$tableUtf[$ch]=$tableBin[$ch]=$ch;else{$tableUtf[$ch]=$ch;$tableBin[$ch]='\\x'.dechex(ord($ch));}}$tableUtf['\\x']=$tableBin['\\x']='\\\\x';}if(is_bool($var)){return"<span>bool</span>(".($var?'TRUE':'FALSE').")\n";}elseif($var===NULL){return"<span>NULL</span>\n";}elseif(is_int($var)){return"<span>int</span>($var)\n";}elseif(is_float($var)){return"<span>float</span>($var)\n";}elseif(is_string($var)){if(self::$maxLen&&strlen($var)>self::$maxLen){$s=htmlSpecialChars(substr($var,0,self::$maxLen),ENT_NOQUOTES).' ... ';}else{$s=htmlSpecialChars($var,ENT_NOQUOTES);}$s=strtr($s,preg_match($re,$s)||preg_last_error()?$tableBin:$tableUtf);return"<span>string</span>(".strlen($var).") \"$s\"\n";}elseif(is_array($var)){$s="<span>array</span>(".count($var).") ";$space=str_repeat($space1='   ',$level);static$marker;if($marker===NULL)$marker=uniqid("\x00",TRUE);if(empty($var)){}elseif(isset($var[$marker])){$s.="{\n$space$space1*RECURSION*\n$space}";}elseif($level<self::$maxDepth||!self::$maxDepth){$s.="<code>{\n";$var[$marker]=0;foreach($var
as$k=>&$v){if($k===$marker)continue;$k=is_int($k)?$k:'"'.strtr($k,preg_match($re,$k)||preg_last_error()?$tableBin:$tableUtf).'"';$s.="$space$space1$k => ".self::_dump($v,$level+1);}unset($var[$marker]);$s.="$space}</code>";}else{$s.="{\n$space$space1...\n$space}";}return$s."\n";}elseif(is_object($var)){$arr=(array)$var;$s="<span>object</span>(".get_class($var).") (".count($arr).") ";$space=str_repeat($space1='   ',$level);static$list=array();if(empty($arr)){$s.="{}";}elseif(in_array($var,$list,TRUE)){$s.="{\n$space$space1*RECURSION*\n$space}";}elseif($level<self::$maxDepth||!self::$maxDepth){$s.="<code>{\n";$list[]=$var;foreach($arr
as$k=>&$v){$m='';if($k[0]==="\x00"){$m=$k[1]==='*'?' <span>protected</span>':' <span>private</span>';$k=substr($k,strrpos($k,"\x00")+1);}$k=strtr($k,preg_match($re,$k)||preg_last_error()?$tableBin:$tableUtf);$s.="$space$space1\"$k\"$m => ".self::_dump($v,$level+1);}array_pop($list);$s.="$space}</code>";}else{$s.="{\n$space$space1...\n$space}";}return$s."\n";}elseif(is_resource($var)){return"<span>resource of type</span>(".get_resource_type($var).")\n";}else{return"<span>unknown type</span>\n";}}public
static
function
timer($name=NULL){static$time=array();$now=microtime(TRUE);$delta=isset($time[$name])?$now-$time[$name]:0;$time[$name]=$now;return$delta;}public
static
function
enable($mode=NULL,$logFile=NULL,$email=NULL){error_reporting(E_ALL|E_STRICT);if(is_bool($mode)){self::$productionMode=$mode;}if(self::$productionMode===self::DETECT){if(class_exists('Environment')){self::$productionMode=Environment::isProduction();}elseif(isset($_SERVER['SERVER_ADDR'])||isset($_SERVER['LOCAL_ADDR'])){$addr=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR'];$oct=explode('.',$addr);self::$productionMode=$addr!=='::1'&&(count($oct)!==4||($oct[0]!=='10'&&$oct[0]!=='127'&&($oct[0]!=='172'||$oct[1]<16||$oct[1]>31)&&($oct[0]!=='169'||$oct[1]!=='254')&&($oct[0]!=='192'||$oct[1]!=='168')));}else{self::$productionMode=!self::$consoleMode;}}if(self::$productionMode&&$logFile!==FALSE){self::$logFile='log/php_error.log';if(class_exists('Environment')){if(is_string($logFile)){self::$logFile=Environment::expand($logFile);}else
try{self::$logFile=Environment::expand('%logDir%/php_error.log');}catch(InvalidStateException$e){}}elseif(is_string($logFile)){self::$logFile=$logFile;}ini_set('error_log',self::$logFile);}if(function_exists('ini_set')){ini_set('display_errors',!self::$productionMode);ini_set('html_errors',!self::$logFile&&!self::$consoleMode);ini_set('log_errors',(bool)self::$logFile);}elseif(ini_get('log_errors')!=(bool)self::$logFile||(ini_get('display_errors')!=!self::$productionMode&&ini_get('display_errors')!==(self::$productionMode?'stderr':'stdout'))){throw
new
NotSupportedException('Function ini_set() must be enabled.');}self::$sendEmails=self::$logFile&&$email;if(self::$sendEmails){if(is_string($email)){self::$emailHeaders['To']=$email;}elseif(is_array($email)){self::$emailHeaders=$email+self::$emailHeaders;}}if(!defined('E_DEPRECATED')){define('E_DEPRECATED',8192);}if(!defined('E_USER_DEPRECATED')){define('E_USER_DEPRECATED',16384);}set_exception_handler(array(__CLASS__,'_exceptionHandler'));set_error_handler(array(__CLASS__,'_errorHandler'));self::$enabled=TRUE;}public
static
function
isEnabled(){return
self::$enabled;}public
static
function
_exceptionHandler(Exception$exception){if(!headers_sent()){header('HTTP/1.1 500 Internal Server Error');}self::processException($exception,TRUE);exit;}public
static
function
_errorHandler($severity,$message,$file,$line,$context){if($severity===E_RECOVERABLE_ERROR||$severity===E_USER_ERROR){throw
new
FatalErrorException($message,0,$severity,$file,$line,$context);}elseif(($severity&error_reporting())!==$severity){return
NULL;}elseif(self::$strictMode){if(!headers_sent()){header('HTTP/1.1 500 Internal Server Error');}self::processException(new
FatalErrorException($message,0,$severity,$file,$line,$context),TRUE);exit;}static$types=array(E_WARNING=>'Warning',E_USER_WARNING=>'Warning',E_NOTICE=>'Notice',E_USER_NOTICE=>'Notice',E_STRICT=>'Strict standards',E_DEPRECATED=>'Deprecated',E_USER_DEPRECATED=>'Deprecated');$type=isset($types[$severity])?$types[$severity]:'Unknown error';if(self::$logFile){if(self::$sendEmails){self::sendEmail("$type: $message in $file on line $line");}return
FALSE;}elseif(!self::$productionMode&&self::$firebugDetected&&!headers_sent()){$message=strip_tags($message);self::fireLog("$type: $message in $file on line $line",self::ERROR);return
NULL;}return
FALSE;}public
static
function
processException(Exception$exception,$outputAllowed=FALSE){if(!self::$enabled){return;}elseif(self::$logFile){error_log("PHP Fatal error:  Uncaught $exception");$file=@strftime('%d-%b-%Y %H-%M-%S ',Debug::$time).strstr(number_format(Debug::$time,4,'~',''),'~');$file=dirname(self::$logFile)."/exception $file.html";self::$logHandle=@fopen($file,'x');if(self::$logHandle){ob_start(array(__CLASS__,'_writeFile'),1);self::_paintBlueScreen($exception);ob_end_flush();fclose(self::$logHandle);}if(self::$sendEmails){self::sendEmail((string)$exception);}}elseif(self::$productionMode){}elseif(self::$consoleMode){if($outputAllowed){echo"$exception\n";foreach(self::$colophons
as$callback){foreach((array)call_user_func($callback,'bluescreen')as$line)echo
strip_tags($line)."\n";}}}elseif(self::$firebugDetected&&self::$ajaxDetected&&!headers_sent()){self::fireLog($exception,self::EXCEPTION);}elseif($outputAllowed){if(!headers_sent()){@ob_end_clean();while(ob_get_level()&&@ob_end_clean());if(in_array('Content-Encoding: gzip',headers_list()))header('Content-Encoding: identity',TRUE);}self::_paintBlueScreen($exception);}elseif(self::$firebugDetected&&!headers_sent()){self::fireLog($exception,self::EXCEPTION);}foreach(self::$onFatalError
as$handler){fixCallback($handler);call_user_func($handler,$exception);}}public
static
function
_paintBlueScreen(Exception$exception){$internals=array();foreach(array('Object','ObjectMixin')as$class){if(class_exists($class,FALSE)){$rc=new
ReflectionClass($class);$internals[$rc->getFileName()]=TRUE;}}if(class_exists('Environment',FALSE)){$application=Environment::getServiceLocator()->hasService('Nette\Application\Application',TRUE)?Environment::getServiceLocator()->getService('Nette\Application\Application'):NULL;}$colophons=self::$colophons;if(!function_exists('_netteDebugPrintCode')){function
_netteDebugPrintCode($file,$line,$count=15){if(function_exists('ini_set')){ini_set('highlight.comment','#999; font-style: italic');ini_set('highlight.default','#000');ini_set('highlight.html','#06b');ini_set('highlight.keyword','#d24; font-weight: bold');ini_set('highlight.string','#080');}$start=max(1,$line-floor($count/2));$source=@file_get_contents($file);if(!$source)return;$source=explode("\n",highlight_string($source,TRUE));$spans=1;echo$source[0];$source=explode('<br />',$source[1]);array_unshift($source,NULL);$i=$start;while(--$i>=1){if(preg_match('#.*(</?span[^>]*>)#',$source[$i],$m)){if($m[1]!=='</span>'){$spans++;echo$m[1];}break;}}$source=array_slice($source,$start,$count,TRUE);end($source);$numWidth=strlen((string)key($source));foreach($source
as$n=>$s){$spans+=substr_count($s,'<span')-substr_count($s,'</span');$s=str_replace(array("\r","\n"),array('',''),$s);if($n===$line){printf("<span class='highlight'>Line %{$numWidth}s:    %s\n</span>%s",$n,strip_tags($s),preg_replace('#[^>]*(<[^>]+>)[^<]*#','$1',$s));}else{printf("<span class='line'>Line %{$numWidth}s:</span>    %s\n",$n,$s);}}echo
str_repeat('</span>',$spans),'</code>';}function
_netteDump($var){return
preg_replace_callback('#(<pre class="dump">|\s+)?(.*)\((\d+)\) <code>#','_netteDumpCb',Debug::dump($var,TRUE));}function
_netteDumpCb($m){return"$m[1]<a href='#' onclick='return !netteToggle(this)'>$m[2]($m[3]) ".(trim($m[1])||$m[3]<7?'<abbr>&#x25bc;</abbr> </a><code>':'<abbr>&#x25ba;</abbr> </a><code class="collapsed">');}function
_netteOpenPanel($name,$collapsed){static$id;$id++;?>
	<div class="panel">
		<h2><a href="#" onclick="return !netteToggle(this, 'pnl<?php echo$id?>')"><?php echo
htmlSpecialChars($name)?> <abbr><?php echo$collapsed?'&#x25ba;':'&#x25bc;'?></abbr></a></h2>

		<div id="pnl<?php echo$id?>" class="<?php echo$collapsed?'collapsed ':''?>inner">
	<?php
}function
_netteClosePanel(){?>
		</div>
	</div>
	<?php
}}static$errorTypes=array(E_ERROR=>'Fatal Error',E_USER_ERROR=>'User Error',E_RECOVERABLE_ERROR=>'Recoverable Error',E_CORE_ERROR=>'Core Error',E_COMPILE_ERROR=>'Compile Error',E_PARSE=>'Parse Error',E_WARNING=>'Warning',E_CORE_WARNING=>'Core Warning',E_COMPILE_WARNING=>'Compile Warning',E_USER_WARNING=>'User Warning',E_NOTICE=>'Notice',E_USER_NOTICE=>'User Notice',E_STRICT=>'Strict',E_DEPRECATED=>'Deprecated',E_USER_DEPRECATED=>'User Deprecated');$title=($exception
instanceof
FatalErrorException&&isset($errorTypes[$exception->getSeverity()]))?$errorTypes[$exception->getSeverity()]:get_class($exception);$rn=0;if(headers_sent()){echo'</pre></xmp></table>';}?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex,noarchive">
	<meta name="generator" content="Nette Framework">

	<title><?php echo
htmlspecialchars($title)?></title><!-- <?php echo$exception->getMessage(),($exception->getCode()?' #'.$exception->getCode():'')?> -->

	<style type="text/css">
	/* <![CDATA[ */
		body {
			margin: 0 0 2em;
			padding: 0;
		}

		#netteBluescreen {
			font: 9pt/1.5 Verdana, sans-serif;
			background: white;
			color: #333;
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			z-index: 23178;
			text-align: left;
		}

		#netteBluescreen * {
			color: inherit;
			background: inherit;
			text-align: inherit;
		}

		#netteBluescreenIcon {
			position: absolute;
			right: .5em;
			top: .5em;
			z-index: 23179;
			text-decoration: none;
			background: red;
			padding: 3px;
		}

		#netteBluescreenIcon abbr {
			color: black !important;
		}

		#netteBluescreen h1 {
			font: 18pt/1.5 Verdana, sans-serif !important;
			margin: .6em 0;
		}

		#netteBluescreen h2 {
			font: 14pt/1.5 sans-serif !important;
			color: #888;
			margin: .6em 0;
		}

		#netteBluescreen a {
			text-decoration: none;
			color: #4197E3;
		}

		#netteBluescreen a abbr {
			font-family: sans-serif;
			color: #999;
		}

		#netteBluescreen h3 {
			font: bold 10pt/1.5 Verdana, sans-serif !important;
			margin: 1em 0;
			padding: 0;
		}

		#netteBluescreen p {
			margin: .8em 0
		}

		#netteBluescreen pre, #netteBluescreen code, #netteBluescreen table {
			font: 9pt/1.5 Consolas, monospace !important;
		}

		#netteBluescreen pre, #netteBluescreen table {
			background: #fffbcc;
			padding: .4em .7em;
			border: 1px dotted silver;
		}

		#netteBluescreen table pre {
			padding: 0;
			margin: 0;
			border: none;
		}

		#netteBluescreen pre.dump span {
			color: #c16549;
		}

		#netteBluescreen pre.dump a {
			color: #333;
		}

		#netteBluescreen div.panel {
			border-bottom: 1px solid #eee;
			padding: 1px 2em;
		}

		#netteBluescreen div.inner {
			padding: 0.1em 1em 1em;
			background: #f5f5f5;
		}

		#netteBluescreen table {
			border-collapse: collapse;
			width: 100%;
		}

		#netteBluescreen td, #netteBluescreen th {
			vertical-align: top;
			text-align: left;
			padding: 2px 3px;
			border: 1px solid #eeeebb;
		}

		#netteBluescreen th {
			width: 10%;
			font-weight: bold;
		}

		#netteBluescreen .odd, #netteBluescreen .odd pre {
			background-color: #faf5c3;
		}

		#netteBluescreen ul {
			font: 7pt/1.5 Verdana, sans-serif !important;
			padding: 1em 2em 50px;
		}

		#netteBluescreen .highlight, #netteBluescreenError {
			background: red;
			color: white;
			font-weight: bold;
			font-style: normal;
			display: block;
		}

		#netteBluescreen .line {
			color: #9e9e7e;
			font-weight: normal;
			font-style: normal;
		}

	/* ]]> */
	</style>


	<script type="text/javascript">
	/* <![CDATA[ */
		document.write('<style> .collapsed { display: none; } <\/style>');

		function netteToggle(link, panelId)
		{
			var arrow = link.getElementsByTagName('abbr')[0];
			var panel = panelId ? document.getElementById(panelId) : link.nextSibling;
			while (panel.nodeType !== 1) panel = panel.nextSibling;
			var collapsed = panel.currentStyle ? panel.currentStyle.display == 'none' : getComputedStyle(panel, null).display == 'none';

			arrow.innerHTML = String.fromCharCode(collapsed ? 0x25bc : 0x25ba);
			panel.style.display = collapsed ? (panel.tagName.toLowerCase() === 'code' ? 'inline' : 'block') : 'none';

			return true;
		}
	/* ]]> */
	</script>
</head>



<body>
<div id="netteBluescreen">
	<a id="netteBluescreenIcon" href="#" onclick="return !netteToggle(this)"><abbr>&#x25bc;</abbr></a

	><div>
		<div id="netteBluescreenError" class="panel">
			<h1><?php echo
htmlspecialchars($title),($exception->getCode()?' #'.$exception->getCode():'')?></h1>

			<p><?php echo
htmlspecialchars($exception->getMessage())?></p>
		</div>



		<?php $ex=$exception;$level=0;?>
		<?php do{?>

			<?php if($level++):?>
				<?php _netteOpenPanel('Caused by',TRUE)?>
				<div class="panel">
					<h1><?php echo
htmlspecialchars(get_class($ex)),($ex->getCode()?' #'.$ex->getCode():'')?></h1>

					<p><?php echo
htmlspecialchars($ex->getMessage())?></p>
				</div>
			<?php endif?>

			<?php $collapsed=isset($internals[$ex->getFile()]);?>
			<?php if(is_file($ex->getFile())):?>
			<?php _netteOpenPanel('Source file',$collapsed)?>
				<p><strong>File:</strong> <?php echo
htmlspecialchars($ex->getFile())?> &nbsp; <strong>Line:</strong> <?php echo$ex->getLine()?></p>
				<pre><?php _netteDebugPrintCode($ex->getFile(),$ex->getLine())?></pre>
			<?php _netteClosePanel()?>
			<?php endif?>



			<?php _netteOpenPanel('Call stack',FALSE)?>
				<ol>
					<?php foreach($ex->getTrace()as$key=>$row):?>
					<li><p>

					<?php if(isset($row['file'])):?>
						<span title="<?php echo
htmlSpecialChars($row['file'])?>"><?php echo
htmlSpecialChars(basename(dirname($row['file']))),'/<b>',htmlSpecialChars(basename($row['file'])),'</b></span> (',$row['line'],')'?>
					<?php else:?>
						&lt;PHP inner-code&gt;
					<?php endif?>

					<?php if(isset($row['file'])&&is_file($row['file'])):?><a href="#" onclick="return !netteToggle(this, 'src<?php echo"$level-$key"?>')">source <abbr>&#x25ba;</abbr></a>&nbsp; <?php endif?>

					<?php if(isset($row['class']))echo$row['class'].$row['type']?>
					<?php echo$row['function']?>

					(<?php if(!empty($row['args'])):?><a href="#" onclick="return !netteToggle(this, 'args<?php echo"$level-$key"?>')">arguments <abbr>&#x25ba;</abbr></a><?php endif?>)
					</p>

					<?php if(!empty($row['args'])):?>
						<div class="collapsed" id="args<?php echo"$level-$key"?>">
						<table>
						<?php

try{$r=isset($row['class'])?new
ReflectionMethod($row['class'],$row['function']):new
ReflectionFunction($row['function']);$params=$r->getParameters();}catch(Exception$e){$params=array();}foreach($row['args']as$k=>$v){echo'<tr><th>',(isset($params[$k])?'$'.$params[$k]->name:"#$k"),'</th><td>';echo
_netteDump($v);echo"</td></tr>\n";}?>
						</table>
						</div>
					<?php endif?>


					<?php if(isset($row['file'])&&is_file($row['file'])):?>
						<pre <?php if(!$collapsed||isset($internals[$row['file']]))echo'class="collapsed"';else$collapsed=FALSE?> id="src<?php echo"$level-$key"?>"><?php _netteDebugPrintCode($row['file'],$row['line'])?></pre>
					<?php endif?>

					</li>
					<?php endforeach?>

					<?php if(!isset($row)):?>
					<li><i>empty</i></li>
					<?php endif?>
				</ol>
			<?php _netteClosePanel()?>



			<?php if($ex
instanceof
IDebuggable):?>
			<?php foreach($ex->getPanels()as$name=>$panel):?>
			<?php _netteOpenPanel($name,empty($panel['expanded']))?>
				<?php echo$panel['content']?>
			<?php _netteClosePanel()?>
			<?php endforeach?>
			<?php endif?>



			<?php if(isset($ex->context)&&is_array($ex->context)):?>
			<?php _netteOpenPanel('Variables',TRUE)?>
			<table>
			<?php

foreach($ex->context
as$k=>$v){echo'<tr><th>$',htmlspecialchars($k),'</th><td>',_netteDump($v),"</td></tr>\n";}?>
			</table>
			<?php _netteClosePanel()?>
			<?php endif?>

		<?php }while((method_exists($ex,'getPrevious')&&$ex=$ex->getPrevious())||(isset($ex->previous)&&$ex=$ex->previous));?>
		<?php while(--$level)_netteClosePanel()?>



		<?php if(!empty($application)):?>
		<?php _netteOpenPanel('Nette Application',TRUE)?>
			<h3>Requests</h3>
			<?php echo
_netteDump($application->getRequests())?>

			<h3>Presenter</h3>
			<?php echo
_netteDump($application->getPresenter())?>
		<?php _netteClosePanel()?>
		<?php endif?>



		<?php _netteOpenPanel('Environment',TRUE)?>
			<?php
$list=get_defined_constants(TRUE);if(!empty($list['user'])):?>
			<h3><a href="#" onclick="return !netteToggle(this, 'pnl-env-const')">Constants <abbr>&#x25bc;</abbr></a></h3>
			<table id="pnl-env-const">
			<?php

foreach($list['user']as$k=>$v){echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th>';echo'<td>',_netteDump($v),"</td></tr>\n";}?>
			</table>
			<?php endif?>


			<h3><a href="#" onclick="return !netteToggle(this, 'pnl-env-files')">Included files <abbr>&#x25ba;</abbr></a>(<?php echo
count(get_included_files())?>)</h3>
			<table id="pnl-env-files" class="collapsed">
			<?php

foreach(get_included_files()as$v){echo'<tr'.($rn++%2?' class="odd"':'').'><td>',htmlspecialchars($v),"</td></tr>\n";}?>
			</table>


			<h3>$_SERVER</h3>
			<?php if(empty($_SERVER)):?>
			<p><i>empty</i></p>
			<?php else:?>
			<table>
			<?php

foreach($_SERVER
as$k=>$v)echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th><td>',_netteDump($v),"</td></tr>\n";?>
			</table>
			<?php endif?>
		<?php _netteClosePanel()?>



		<?php _netteOpenPanel('HTTP request',TRUE)?>
			<?php if(function_exists('apache_request_headers')):?>
			<h3>Headers</h3>
			<table>
			<?php

foreach(apache_request_headers()as$k=>$v)echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th><td>',htmlspecialchars($v),"</td></tr>\n";?>
			</table>
			<?php endif?>


			<?php foreach(array('_GET','_POST','_COOKIE')as$name):?>
			<h3>$<?php echo$name?></h3>
			<?php if(empty($GLOBALS[$name])):?>
			<p><i>empty</i></p>
			<?php else:?>
			<table>
			<?php

foreach($GLOBALS[$name]as$k=>$v)echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th><td>',_netteDump($v),"</td></tr>\n";?>
			</table>
			<?php endif?>
			<?php endforeach?>
		<?php _netteClosePanel()?>



		<?php _netteOpenPanel('HTTP response',TRUE)?>
			<h3>Headers</h3>
			<?php if(headers_list()):?>
			<pre><?php

foreach(headers_list()as$s)echo
htmlspecialchars($s),'<br>';?></pre>
			<?php else:?>
			<p><i>no headers</i></p>
			<?php endif?>
		<?php _netteClosePanel()?>


		<ul>
			<?php foreach($colophons
as$callback):?>
			<?php foreach((array)call_user_func($callback,'bluescreen')as$line):?><li><?php echo$line,"\n"?></li><?php endforeach?>
			<?php endforeach?>
		</ul>
	</div>
</div>

<script type="text/javascript">
	document.body.appendChild(document.getElementById('netteBluescreen'));
</script>
</body>
</html><?php }public
static
function
_writeFile($buffer){fwrite(self::$logHandle,$buffer);}private
static
function
sendEmail($message){$monitorFile=self::$logFile.'.monitor';if(@filemtime($monitorFile)+self::$emailSnooze<time()&&@file_put_contents($monitorFile,'sent')){call_user_func(self::$mailer,$message);}}private
static
function
defaultMailer($message){$host=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'');$headers=str_replace(array('%host%','%date%','%message%'),array($host,@date('Y-m-d H:i:s',Debug::$time),$message),self::$emailHeaders);$subject=$headers['Subject'];$to=$headers['To'];$body=$headers['Body'];unset($headers['Subject'],$headers['To'],$headers['Body']);$header='';foreach($headers
as$key=>$value){$header.="$key: $value\r\n";}$body=str_replace("\r\n","\n",$body);if(PHP_OS!='Linux')$body=str_replace("\n","\r\n",$body);mail($to,$subject,$body,$header);}public
static
function
enableProfiler(){self::$enabledProfiler=TRUE;}public
static
function
disableProfiler(){self::$enabledProfiler=FALSE;}public
static
function
addColophon($callback){fixCallback($callback);if(!is_callable($callback)){$able=is_callable($callback,TRUE,$textual);throw
new
InvalidArgumentException("Colophon handler '$textual' is not ".($able?'callable.':'valid PHP callback.'));}if(!in_array($callback,self::$colophons,TRUE)){self::$colophons[]=$callback;}}private
static
function
getDefaultColophons($sender){if($sender==='profiler'){$arr[]='Elapsed time: <b>'.number_format((microtime(TRUE)-Debug::$time)*1000,1,'.',' ').'</b> ms | Allocated memory: <b>'.number_format(memory_get_peak_usage()/1000,1,'.',' ').'</b> kB';foreach((array)self::$counters
as$name=>$value){if(is_array($value))$value=implode(', ',$value);$arr[]=htmlSpecialChars($name).' = <strong>'.htmlSpecialChars($value).'</strong>';}$autoloaded=class_exists('AutoLoader',FALSE)?AutoLoader::$count:0;$s='<span>'.count(get_included_files()).'/'.$autoloaded.' files</span>, ';$exclude=array('stdClass','Exception','ErrorException','Traversable','IteratorAggregate','Iterator','ArrayAccess','Serializable','Closure');foreach(get_loaded_extensions()as$ext){$ref=new
ReflectionExtension($ext);$exclude=array_merge($exclude,$ref->getClassNames());}$classes=array_diff(get_declared_classes(),$exclude);$intf=array_diff(get_declared_interfaces(),$exclude);$func=get_defined_functions();$func=(array)@$func['user'];$consts=get_defined_constants(TRUE);$consts=array_keys((array)@$consts['user']);foreach(array('classes','intf','func','consts')as$item){$s.='<span '.($$item?'title="'.implode(", ",$$item).'"':'').'>'.count($$item).' '.$item.'</span>, ';}$arr[]=$s;}if($sender==='bluescreen'){$arr[]='Report generated at '.@date('Y/m/d H:i:s',Debug::$time);if(isset($_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI'])){$url=(isset($_SERVER['HTTPS'])&&strcasecmp($_SERVER['HTTPS'],'off')?'https://':'http://').htmlSpecialChars($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);$arr[]='<a href="'.$url.'">'.$url.'</a>';}$arr[]='PHP '.htmlSpecialChars(PHP_VERSION);if(isset($_SERVER['SERVER_SOFTWARE']))$arr[]=htmlSpecialChars($_SERVER['SERVER_SOFTWARE']);$arr[]=htmlSpecialChars(Framework::NAME.' '.Framework::VERSION).' <i>(revision '.htmlSpecialChars(Framework::REVISION).')</i>';}return$arr;}public
static
function
fireDump($var,$key){self::fireSend(2,array((string)$key=>$var));return$var;}public
static
function
fireLog($message,$priority=self::LOG,$label=NULL){if($message
instanceof
Exception){if($priority!==self::EXCEPTION&&$priority!==self::TRACE){$priority=self::TRACE;}$message=array('Class'=>get_class($message),'Message'=>$message->getMessage(),'File'=>$message->getFile(),'Line'=>$message->getLine(),'Trace'=>$message->getTrace(),'Type'=>'','Function'=>'');foreach($message['Trace']as&$row){if(empty($row['file']))$row['file']='?';if(empty($row['line']))$row['line']='?';}}elseif($priority===self::GROUP_START){$label=$message;$message=NULL;}return
self::fireSend(1,self::replaceObjects(array(array('Type'=>$priority,'Label'=>$label),$message)));}private
static
function
fireSend($index,$payload){if(self::$productionMode)return
NULL;if(headers_sent())return
FALSE;header('X-Wf-Protocol-nette: http://meta.wildfirehq.org/Protocol/JsonStream/0.2');header('X-Wf-nette-Plugin-1: http://meta.firephp.org/Wildfire/Plugin/FirePHP/Library-FirePHPCore/0.2.0');if($index===1){header('X-Wf-nette-Structure-1: http://meta.firephp.org/Wildfire/Structure/FirePHP/FirebugConsole/0.1');}elseif($index===2){header('X-Wf-nette-Structure-2: http://meta.firephp.org/Wildfire/Structure/FirePHP/Dump/0.1');}$payload=json_encode($payload);static$counter;foreach(str_split($payload,4990)as$s){$num=++$counter;header("X-Wf-nette-$index-1-n$num: |$s|\\");}header("X-Wf-nette-$index-1-n$num: |$s|");return
TRUE;}static
private
function
replaceObjects($val){if(is_object($val)){return'object '.get_class($val).'';}elseif(is_string($val)){return@iconv('UTF-16','UTF-8//IGNORE',iconv('UTF-8','UTF-16//IGNORE',$val));}elseif(is_array($val)){foreach($val
as$k=>$v){unset($val[$k]);$k=@iconv('UTF-16','UTF-8//IGNORE',iconv('UTF-8','UTF-16//IGNORE',$k));$val[$k]=self::replaceObjects($v);}}return$val;}}Debug::_init();final
class
Environment{const
DEVELOPMENT='development';const
PRODUCTION='production';const
CONSOLE='console';const
LAB='lab';const
DEBUG='debug';const
PERFORMANCE='performance';private
static$configurator;private
static$mode=array();private
static$config;private
static$serviceLocator;private
static$vars=array('encoding'=>array('UTF-8',FALSE),'lang'=>array('en',FALSE),'cacheBase'=>array('%tempDir%',TRUE),'tempDir'=>array('%appDir%/temp',TRUE),'logDir'=>array('%appDir%/log',TRUE));private
static$aliases=array('getHttpRequest'=>'Nette\Web\IHttpRequest','getHttpResponse'=>'Nette\Web\IHttpResponse','getApplication'=>'Nette\Application\Application','getUser'=>'Nette\Web\IUser');final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
setConfigurator(Configurator$configurator){self::$configurator=$configurator;}public
static
function
getConfigurator(){if(self::$configurator===NULL){self::$configurator=new
Configurator;}return
self::$configurator;}public
static
function
setName($name){if(!isset(self::$vars['environment'])){self::setVariable('environment',$name,FALSE);}else{throw
new
InvalidStateException('Environment name has been already set.');}}public
static
function
getName(){$name=self::getVariable('environment');if($name===NULL){$name=self::getConfigurator()->detect('environment');self::setVariable('environment',$name,FALSE);}return$name;}public
static
function
setMode($mode,$value=TRUE){self::$mode[$mode]=(bool)$value;}public
static
function
getMode($mode){if(isset(self::$mode[$mode])){return
self::$mode[$mode];}else{return
self::$mode[$mode]=self::getConfigurator()->detect($mode);}}public
static
function
isConsole(){return
self::getMode('console');}public
static
function
isProduction(){return
self::getMode('production');}public
static
function
isDebugging(){throw
new
DeprecatedException;}public
static
function
setVariable($name,$value,$expand=TRUE){if(!is_string($value)){$expand=FALSE;}self::$vars[$name]=array($value,(bool)$expand);}public
static
function
getVariable($name,$default=NULL){if(isset(self::$vars[$name])){list($var,$exp)=self::$vars[$name];if($exp){$var=self::expand($var);self::$vars[$name]=array($var,FALSE);}return$var;}else{$const=strtoupper(preg_replace('#(.)([A-Z]+)#','$1_$2',$name));$list=get_defined_constants(TRUE);if(isset($list['user'][$const])){self::$vars[$name]=array($list['user'][$const],FALSE);return$list['user'][$const];}else{return$default;}}}public
static
function
getVariables(){$res=array();foreach(self::$vars
as$name=>$foo){$res[$name]=self::getVariable($name);}return$res;}public
static
function
expand($var){if(is_string($var)&&strpos($var,'%')!==FALSE){return@preg_replace_callback('#%([a-z0-9_-]*)%#i',array(__CLASS__,'expandCb'),$var);}return$var;}private
static
function
expandCb($m){list(,$var)=$m;if($var==='')return'%';static$livelock;if(isset($livelock[$var])){throw
new
InvalidStateException("Circular reference detected for variables: ".implode(', ',array_keys($livelock)).".");}try{$livelock[$var]=TRUE;$val=self::getVariable($var);unset($livelock[$var]);}catch(Exception$e){$livelock=array();throw$e;}if($val===NULL){throw
new
InvalidStateException("Unknown environment variable '$var'.");}elseif(!is_scalar($val)){throw
new
InvalidStateException("Environment variable '$var' is not scalar.");}return$val;}public
static
function
getServiceLocator(){if(self::$serviceLocator===NULL){self::$serviceLocator=self::getConfigurator()->createServiceLocator();}return
self::$serviceLocator;}public
static
function
getService($name,array$options=NULL){return
self::getServiceLocator()->getService($name,$options);}public
static
function
setServiceAlias($service,$alias){self::$aliases['get'.ucfirst($alias)]=$service;}public
static
function
__callStatic($name,$args){if(isset(self::$aliases[$name])){return
self::getServiceLocator()->getService(self::$aliases[$name],$args);}else{throw
new
MemberAccessException("Call to undefined static method Nette\\Environment::$name().");}}public
static
function
getHttpRequest(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}public
static
function
getHttpResponse(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}public
static
function
getApplication(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}public
static
function
getUser(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}public
static
function
getCache($namespace=''){return
new
Cache(self::getService('Nette\Caching\ICacheStorage'),$namespace);}public
static
function
getSession($namespace=NULL){$handler=self::getService('Nette\Web\Session');return$namespace===NULL?$handler:$handler->getNamespace($namespace);}public
static
function
loadConfig($file=NULL){return
self::$config=self::getConfigurator()->loadConfig($file);}public
static
function
getConfig($key=NULL,$default=NULL){if(func_num_args()){return
isset(self::$config[$key])?self::$config[$key]:$default;}else{return
self::$config;}}}abstract
class
FreezableObject
extends
Object{private$frozen=FALSE;public
function
freeze(){$this->frozen=TRUE;}final
public
function
isFrozen(){return$this->frozen;}public
function
__clone(){$this->frozen=FALSE;}protected
function
updating(){if($this->frozen){throw
new
InvalidStateException("Cannot modify a frozen object '$this->class'.");}}}interface
IDebuggable{function
getPanels();}interface
IServiceLocator{function
addService($name,$service,$singleton=TRUE,array$options=NULL);function
getService($name,array$options=NULL);function
removeService($name);function
hasService($name);}interface
ITranslator{function
translate($message,$count=NULL);}class
Image
extends
Object{const
ENLARGE=1;const
STRETCH=2;const
FILL=4;const
JPEG=IMAGETYPE_JPEG;const
PNG=IMAGETYPE_PNG;const
GIF=IMAGETYPE_GIF;const
EMPTY_GIF="GIF89a\x01\x00\x01\x00\x80\x00\x00\x00\x00\x00\x00\x00\x00!\xf9\x04\x01\x00\x00\x00\x00,\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02D\x01\x00;";public
static$useImageMagick=FALSE;private$image;public
static
function
rgb($red,$green,$blue,$transparency=0){return
array('red'=>max(0,min(255,(int)$red)),'green'=>max(0,min(255,(int)$green)),'blue'=>max(0,min(255,(int)$blue)),'alpha'=>max(0,min(127,(int)$transparency)));}public
static
function
fromFile($file,&$format=NULL){if(!extension_loaded('gd')){throw
new
Exception("PHP extension GD is not loaded.");}$info=@getimagesize($file);if(self::$useImageMagick&&(empty($info)||$info[0]*$info[1]>2e6)){return
new
ImageMagick($file,$format);}switch($format=$info[2]){case
self::JPEG:return
new
self(imagecreatefromjpeg($file));case
self::PNG:return
new
self(imagecreatefrompng($file));case
self::GIF:return
new
self(imagecreatefromgif($file));default:if(self::$useImageMagick){return
new
ImageMagick($file,$format);}throw
new
Exception("Unknown image type or file '$file' not found.");}}public
static
function
fromString($s,&$format=NULL){if(!extension_loaded('gd')){throw
new
Exception("PHP extension GD is not loaded.");}if(strncmp($s,"\xff\xd8",2)===0){$format=self::JPEG;}elseif(strncmp($s,"\x89PNG",4)===0){$format=self::PNG;}elseif(strncmp($s,"GIF",3)===0){$format=self::GIF;}else{$format=NULL;}return
new
self(imagecreatefromstring($s));}public
static
function
fromBlank($width,$height,$color=NULL){if(!extension_loaded('gd')){throw
new
Exception("PHP extension GD is not loaded.");}$width=(int)$width;$height=(int)$height;if($width<1||$height<1){throw
new
InvalidArgumentException('Image width and height must be greater than zero.');}$image=imagecreatetruecolor($width,$height);if(is_array($color)){$color+=array('alpha'=>0);$color=imagecolorallocatealpha($image,$color['red'],$color['green'],$color['blue'],$color['alpha']);imagealphablending($image,FALSE);imagefilledrectangle($image,0,0,$width-1,$height-1,$color);imagealphablending($image,TRUE);}return
new
self($image);}public
function
__construct($image){$this->setImageResource($image);}public
function
getWidth(){return
imagesx($this->image);}public
function
getHeight(){return
imagesy($this->image);}protected
function
setImageResource($image){if(!is_resource($image)||get_resource_type($image)!=='gd'){throw
new
InvalidArgumentException('Image is not valid.');}$this->image=$image;return$this;}public
function
getImageResource(){return$this->image;}public
function
resize($newWidth,$newHeight,$flags=0){list($newWidth,$newHeight)=$this->calculateSize($newWidth,$newHeight,$flags);$newImage=self::fromBlank($newWidth,$newHeight,self::RGB(0,0,0,127))->getImageResource();imagecopyresampled($newImage,$this->getImageResource(),0,0,0,0,$newWidth,$newHeight,$this->getWidth(),$this->getHeight());$this->image=$newImage;return$this;}public
function
calculateSize($newWidth,$newHeight,$flags=0){$width=$this->getWidth();$height=$this->getHeight();if(substr($newWidth,-1)==='%'){$newWidth=round($width/100*$newWidth);$flags|=self::ENLARGE;$percents=TRUE;}else{$newWidth=(int)$newWidth;}if(substr($newHeight,-1)==='%'){$newHeight=round($height/100*$newHeight);$flags|=empty($percents)?self::ENLARGE:self::STRETCH;}else{$newHeight=(int)$newHeight;}if($flags&self::STRETCH){if($newWidth<1||$newHeight<1){throw
new
InvalidArgumentException('For stretching must be both width and height specified.');}if(($flags&self::ENLARGE)===0){$newWidth=round($width*min(1,$newWidth/$width));$newHeight=round($height*min(1,$newHeight/$height));}}else{if($newWidth<1&&$newHeight<1){throw
new
InvalidArgumentException('At least width or height must be specified.');}$scale=array();if($newWidth>0){$scale[]=$newWidth/$width;}if($newHeight>0){$scale[]=$newHeight/$height;}if($flags&self::FILL){$scale=array(max($scale));}if(($flags&self::ENLARGE)===0){$scale[]=1;}$scale=min($scale);$newWidth=round($width*$scale);$newHeight=round($height*$scale);}return
array($newWidth,$newHeight);}public
function
crop($left,$top,$width,$height){if(substr($left,-1)==='%'){$left=round(($this->getWidth()-$width)/100*$left);}if(substr($top,-1)==='%'){$top=round(($this->getHeight()-$height)/100*$top);}$left=max(0,(int)$left);$top=max(0,(int)$top);$width=min((int)$width,$this->getWidth()-$left);$height=min((int)$height,$this->getHeight()-$top);$newImage=self::fromBlank($width,$height,self::RGB(0,0,0,127))->getImageResource();imagecopy($newImage,$this->getImageResource(),0,0,$left,$top,$width,$height);$this->image=$newImage;return$this;}public
function
sharpen(){imageconvolution($this->getImageResource(),array(array(-1,-1,-1),array(-1,24,-1),array(-1,-1,-1)),16,0);return$this;}public
function
place(Image$image,$left=0,$top=0,$opacity=100){$opacity=max(0,min(100,(int)$opacity));if(substr($left,-1)==='%'){$left=round(($this->getWidth()-$image->getWidth())/100*$left);}if(substr($top,-1)==='%'){$top=round(($this->getHeight()-$image->getHeight())/100*$top);}if($opacity===100){imagecopy($this->getImageResource(),$image->getImageResource(),$left,$top,0,0,$image->getWidth(),$image->getHeight());}elseif($opacity<>0){imagecopymerge($this->getImageResource(),$image->getImageResource(),$left,$top,0,0,$image->getWidth(),$image->getHeight(),$opacity);}return$this;}public
function
save($file=NULL,$quality=NULL,$type=NULL){if($type===NULL){switch(strtolower(pathinfo($file,PATHINFO_EXTENSION))){case'jpg':case'jpeg':$type=self::JPEG;break;case'png':$type=self::PNG;break;case'gif':$type=self::GIF;}}switch($type){case
self::JPEG:$quality=$quality===NULL?85:max(0,min(100,(int)$quality));return
imagejpeg($this->getImageResource(),$file,$quality);case
self::PNG:$quality=$quality===NULL?9:max(0,min(9,(int)$quality));return
imagepng($this->getImageResource(),$file,$quality);case
self::GIF:return
imagegif($this->getImageResource(),$file);default:throw
new
Exception("Unsupported image type.");}}public
function
toString($type=self::JPEG,$quality=NULL){ob_start();$this->save(NULL,$quality,$type);return
ob_get_clean();}public
function
__toString(){try{return$this->toString();}catch(Exception$e){trigger_error($e->getMessage(),E_USER_WARNING);return'';}}public
function
send($type=self::JPEG,$quality=NULL){if($type!==self::GIF&&$type!==self::PNG&&$type!==self::JPEG){throw
new
Exception("Unsupported image type.");}header('Content-Type: '.image_type_to_mime_type($type));return$this->save(NULL,$quality,$type);}public
function
__call($name,$args){$function='image'.$name;if(function_exists($function)){foreach($args
as$key=>$value){if($value
instanceof
self){$args[$key]=$value->getImageResource();}elseif(is_array($value)&&isset($value['red'])){$args[$key]=imagecolorallocatealpha($this->getImageResource(),$value['red'],$value['green'],$value['blue'],$value['alpha']);}}array_unshift($args,$this->getImageResource());$res=call_user_func_array($function,$args);return
is_resource($res)?new
self($res):$res;}return
parent::__call($name,$args);}}class
ImageMagick
extends
Image{public
static$path='';public
static$tempDir;private$file;private$isTemporary=FALSE;private$width;private$height;public
function
__construct($file,&$format=NULL){if(!is_file($file)){throw
new
InvalidArgumentException('File not found.');}$format=$this->setFile(realpath($file));if($format==='JPEG')$format=self::JPEG;elseif($format==='PNG')$format=self::PNG;elseif($format==='GIF')$format=self::GIF;}public
function
getWidth(){return$this->file===NULL?parent::getWidth():$this->width;}public
function
getHeight(){return$this->file===NULL?parent::getHeight():$this->height;}public
function
getImageResource(){if($this->file!==NULL){if(!$this->isTemporary){$this->execute("convert -strip %input %output",self::PNG);}$this->setImageResource(imagecreatefrompng($this->file));if($this->isTemporary){unlink($this->file);}$this->file=NULL;}return
parent::getImageResource();}public
function
resize($newWidth,$newHeight,$flags=0){if($this->file===NULL){return
parent::resize($newWidth,$newHeight,$flags);}list($newWidth,$newHeight)=$this->calculateSize($newWidth,$newHeight,$flags);$this->execute("convert -resize {$newWidth}x{$newHeight}! -strip %input %output",self::PNG);return$this;}public
function
crop($left,$top,$width,$height){if($this->file===NULL){return
parent::crop($left,$top,$width,$height);}$left=max(0,(int)$left);$top=max(0,(int)$top);$width=min((int)$width,$this->getWidth()-$left);$height=min((int)$height,$this->getHeight()-$top);$this->execute("convert -crop {$width}x{$height}+{$left}+{$top} -strip %input %output",self::PNG);return$this;}public
function
save($file=NULL,$quality=NULL,$type=NULL){if($this->file===NULL){return
parent::save($file,$quality,$type);}$quality=$quality===NULL?'':'-quality '.max(0,min(100,(int)$quality));if($file===NULL){$this->execute("convert $quality -strip %input %output",$type===NULL?self::PNG:$type);readfile($this->file);}else{$this->execute("convert $quality -strip %input %output",(string)$file);}return
TRUE;}private
function
setFile($file){$this->file=$file;$res=$this->execute('identify -format "%w,%h,%m" '.escapeshellarg($this->file));if(!$res){throw
new
Exception("Unknown image type in file '$file' or ImageMagick not available.");}list($this->width,$this->height,$format)=explode(',',$res,3);return$format;}private
function
execute($command,$output=NULL){$command=str_replace('%input',escapeshellarg($this->file),$command);if($output){$newFile=is_string($output)?$output:(self::$tempDir?self::$tempDir:dirname($this->file)).'/_tempimage'.uniqid().image_type_to_extension($output);$command=str_replace('%output',escapeshellarg($newFile),$command);}$lines=array();exec(self::$path.$command,$lines,$status);if($output){if($status!=0){throw
new
Exception("Unknown error while calling ImageMagick.");}if($this->isTemporary){unlink($this->file);}$this->setFile($newFile);$this->isTemporary=!is_string($output);}return$lines?$lines[0]:FALSE;}public
function
__destruct(){if($this->file!==NULL&&$this->isTemporary){unlink($this->file);}}}class
InstanceFilterIterator
extends
FilterIterator
implements
Countable{private$type;public
function
__construct(Iterator$iterator,$type){$this->type=$type;parent::__construct($iterator);}public
function
accept(){return$this->current()instanceof$this->type;}public
function
count(){return
iterator_count($this);}}class
Paginator
extends
Object{private$base=1;private$itemsPerPage;private$page;private$itemCount=0;public
function
setPage($page){$this->page=(int)$page;return$this;}public
function
getPage(){return$this->base+$this->getPageIndex();}public
function
getFirstPage(){return$this->base;}public
function
getLastPage(){return$this->base+max(0,$this->getPageCount()-1);}public
function
setBase($base){$this->base=(int)$base;return$this;}public
function
getBase(){return$this->base;}protected
function
getPageIndex(){return
min(max(0,$this->page-$this->base),max(0,$this->getPageCount()-1));}public
function
isFirst(){return$this->getPageIndex()===0;}public
function
isLast(){return$this->getPageIndex()===$this->getPageCount()-1;}public
function
getPageCount(){return(int)ceil($this->itemCount/$this->itemsPerPage);}public
function
setItemsPerPage($itemsPerPage){$this->itemsPerPage=max(1,(int)$itemsPerPage);return$this;}public
function
getItemsPerPage(){return$this->itemsPerPage;}public
function
setItemCount($itemCount){$this->itemCount=$itemCount===FALSE?PHP_INT_MAX:max(0,(int)$itemCount);return$this;}public
function
getItemCount(){return$this->itemCount;}public
function
getOffset(){return$this->getPageIndex()*$this->itemsPerPage;}public
function
getCountdownOffset(){return
max(0,$this->itemCount-($this->getPageIndex()+1)*$this->itemsPerPage);}public
function
getLength(){return
min($this->itemsPerPage,$this->itemCount-$this->getPageIndex()*$this->itemsPerPage);}}class
ServiceLocator
extends
Object
implements
IServiceLocator{private$parent;private$registry=array();private$factories=array();public
function
__construct(IServiceLocator$parent=NULL){$this->parent=$parent;}public
function
addService($name,$service,$singleton=TRUE,array$options=NULL){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);if(isset($this->registry[$lower])){throw
new
AmbiguousServiceException("Service named '$name' has been already registered.");}if(is_object($service)){if(!$singleton||$options){throw
new
InvalidArgumentException("Service named '$name' is an instantiated object and must therefore be singleton without options.");}$this->registry[$lower]=$service;}else{if(!$service){throw
new
InvalidArgumentException("Service named '$name' is empty.");}$this->factories[$lower]=array($service,$singleton,$options);}}public
function
removeService($name){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);unset($this->registry[$lower],$this->factories[$lower]);}public
function
getService($name,array$options=NULL){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);if(isset($this->registry[$lower])){if($options){throw
new
InvalidArgumentException("Service named '$name' is singleton and therefore can not have options.");}return$this->registry[$lower];}elseif(isset($this->factories[$lower])){list($factory,$singleton,$defOptions)=$this->factories[$lower];if($singleton&&$options){throw
new
InvalidArgumentException("Service named '$name' is singleton and therefore can not have options.");}elseif($defOptions){$options=$options?$options+$defOptions:$defOptions;}if(is_string($factory)&&strpos($factory,':')===FALSE){fixNamespace($factory);if(!class_exists($factory)){throw
new
AmbiguousServiceException("Cannot instantiate service '$name', class '$factory' not found.");}$service=new$factory;if($options&&method_exists($service,'setOptions')){$service->setOptions($options);}}else{fixCallback($factory);if(!is_callable($factory)){$able=is_callable($factory,TRUE,$textual);throw
new
AmbiguousServiceException("Cannot instantiate service '$name', handler '$textual' is not ".($able?'callable.':'valid PHP callback.'));}$service=call_user_func($factory,$options);if(!is_object($service)){$able=is_callable($factory,TRUE,$textual);throw
new
AmbiguousServiceException("Cannot instantiate service '$name', value returned by '$textual' is not object.");}}if($singleton){$this->registry[$lower]=$service;unset($this->factories[$lower]);}return$service;}if($this->parent!==NULL){return$this->parent->getService($name,$options);}else{throw
new
InvalidStateException("Service '$name' not found.");}}public
function
hasService($name,$created=FALSE){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);return
isset($this->registry[$lower])||(!$created&&isset($this->factories[$lower]))||($this->parent!==NULL&&$this->parent->hasService($name,$created));}public
function
getParent(){return$this->parent;}}class
AmbiguousServiceException
extends
Exception{}class
SmartCachingIterator
extends
CachingIterator
implements
Countable{private$counter=0;public
function
__construct($iterator){if(is_array($iterator)||$iterator
instanceof
stdClass){parent::__construct(new
ArrayIterator($iterator),0);}elseif($iterator
instanceof
IteratorAggregate){parent::__construct($iterator->getIterator(),0);}elseif($iterator
instanceof
Iterator){parent::__construct($iterator,0);}else{throw
new
InvalidArgumentException("Argument passed to ".__METHOD__." must be an array or interface Iterator provider, ".(is_object($iterator)?get_class($iterator):gettype($iterator))." given.");}}public
function
isFirst(){return$this->counter===1;}public
function
isLast(){return!$this->hasNext();}public
function
isEmpty(){return$this->counter===0;}public
function
isOdd(){return$this->counter
%
2===1;}public
function
isEven(){return$this->counter
%
2===0;}public
function
getCounter(){return$this->counter;}public
function
count(){$inner=$this->getInnerIterator();if($inner
instanceof
Countable){return$inner->count();}else{throw
new
NotSupportedException('Iterator is not countable.');}}public
function
next(){parent::next();if(parent::valid()){$this->counter++;}}public
function
rewind(){parent::rewind();$this->counter=parent::valid()?1:0;}public
function
getNextKey(){return$this->getInnerIterator()->key();}public
function
getNextValue(){return$this->getInnerIterator()->current();}public
function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}public
function&__get($name){return
ObjectMixin::get($this,$name);}public
function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}public
function
__isset($name){return
ObjectMixin::has($this,$name);}public
function
__unset($name){$class=get_class($this);throw
new
MemberAccessException("Cannot unset the property $class::\$$name.");}}final
class
String{final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
checkEncoding($s,$encoding='UTF-8'){return$s===self::fixEncoding($s,$encoding);}public
static
function
fixEncoding($s,$encoding='UTF-8'){return@iconv('UTF-16',$encoding.'//IGNORE',iconv($encoding,'UTF-16//IGNORE',$s));}public
static
function
chr($code,$encoding='UTF-8'){return
iconv('UTF-32BE',$encoding.'//IGNORE',pack('N',$code));}public
static
function
startsWith($haystack,$needle){return
strncmp($haystack,$needle,strlen($needle))===0;}public
static
function
endsWith($haystack,$needle){return
strlen($needle)===0||substr($haystack,-strlen($needle))===$needle;}public
static
function
normalize($s){$s=str_replace("\r\n","\n",$s);$s=strtr($s,"\r","\n");$s=preg_replace('#[\x00-\x08\x0B-\x1F]+#','',$s);$s=preg_replace("#[\t ]+$#m",'',$s);$s=trim($s,"\n");return$s;}public
static
function
webalize($s,$charlist=NULL,$lower=TRUE){$s=strtr($s,'`\'"^~','-----');if(ICONV_IMPL==='glibc'){$s=@iconv('UTF-8','WINDOWS-1250//TRANSLIT',$s);$s=strtr($s,"\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2"."\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe","ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");}else{$s=@iconv('UTF-8','ASCII//TRANSLIT',$s);}$s=str_replace(array('`',"'",'"','^','~'),'',$s);if($lower)$s=strtolower($s);$s=preg_replace('#[^a-z0-9'.preg_quote($charlist,'#').']+#i','-',$s);$s=trim($s,'-');return$s;}public
static
function
truncate($s,$maxLen,$append="\xE2\x80\xA6"){if(iconv_strlen($s,'UTF-8')>$maxLen){$maxLen=$maxLen-iconv_strlen($append,'UTF-8');if($maxLen<1){return$append;}elseif(preg_match('#^.{1,'.$maxLen.'}(?=[\s\x00-@\[-`{-~])#us',$s,$matches)){return$matches[0].$append;}else{return
iconv_substr($s,0,$maxLen,'UTF-8').$append;}}return$s;}public
static
function
indent($s,$level=1,$chars="\t"){return$level<1?$s:preg_replace('#(?:^|[\r\n]+)(?=[^\r\n])#','$0'.str_repeat($chars,$level),$s);}public
static
function
lower($s){return
mb_strtolower($s,'UTF-8');}public
static
function
upper($s){return
mb_strtoupper($s,'UTF-8');}public
static
function
capitalize($s){return
mb_convert_case($s,MB_CASE_TITLE,'UTF-8');}public
static
function
trim($s,$charlist=" \t\n\r\0\x0B\xC2\xA0"){$charlist=preg_quote($charlist,'#');return
preg_replace('#^['.$charlist.']+|['.$charlist.']+$#u','',$s);}}final
class
Tools{const
MINUTE=60;const
HOUR=3600;const
DAY=86400;const
WEEK=604800;const
MONTH=2629800;const
YEAR=31557600;final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
iniFlag($var){$status=strtolower(ini_get($var));return$status==='on'||$status==='true'||$status==='yes'||$status
%
256;}public
static
function
defaultize(&$var,$default){if($var===NULL)$var=$default;}public
static
function
glob($pattern,$flags=0){$files=glob($pattern,$flags);if(!is_array($files)){$files=array();}$dirs=glob(dirname($pattern).'/*',$flags|GLOB_ONLYDIR);if(is_array($dirs)){$mask=basename($pattern);foreach($dirs
as$dir){$files=array_merge($files,self::glob($dir.'/'.$mask,$flags));}}return$files;}private
static$errorMsg;public
static
function
tryError($level=E_ALL){set_error_handler(array(__CLASS__,'_errorHandler'),$level);self::$errorMsg=NULL;}public
static
function
catchError(&$message){restore_error_handler();$message=self::$errorMsg;self::$errorMsg=NULL;return$message!==NULL;}public
static
function
_errorHandler($code,$message){if(ini_get('html_errors')){$message=strip_tags($message);$message=html_entity_decode($message);}if(($a=strpos($message,': '))!==FALSE){$message=substr($message,$a+2);}self::$errorMsg=$message;}}interface
INamingContainer
extends
IComponentContainer{}class
FormContainer
extends
ComponentContainer
implements
ArrayAccess,INamingContainer{protected$currentGroup;protected$valid;public
function
setDefaults($values,$erase=FALSE){$form=$this->getForm(FALSE);if(!$form||!$form->isAnchored()||!$form->isSubmitted()){$this->setValues($values,$erase);}return$this;}public
function
setValues($values,$erase=FALSE){if($values
instanceof
Traversable){$values=iterator_to_array($values);}elseif(!is_array($values)){throw
new
InvalidArgumentException("Values must be an array, ".gettype($values)." given.");}$cursor=&$values;$iterator=$this->getComponents(TRUE);foreach($iterator
as$name=>$control){$sub=$iterator->getSubIterator();if(!isset($sub->cursor)){$sub->cursor=&$cursor;}if($control
instanceof
IFormControl){if((is_array($sub->cursor)||$sub->cursor
instanceof
ArrayAccess)&&array_key_exists($name,$sub->cursor)){$control->setValue($sub->cursor[$name]);}elseif($erase){$control->setValue(NULL);}}if($control
instanceof
INamingContainer){if((is_array($sub->cursor)||$sub->cursor
instanceof
ArrayAccess)&&isset($sub->cursor[$name])){$cursor=&$sub->cursor[$name];}else{unset($cursor);$cursor=NULL;}}}return$this;}public
function
getValues(){$values=array();$cursor=&$values;$iterator=$this->getComponents(TRUE);foreach($iterator
as$name=>$control){$sub=$iterator->getSubIterator();if(!isset($sub->cursor)){$sub->cursor=&$cursor;}if($control
instanceof
IFormControl&&!$control->isDisabled()&&!($control
instanceof
ISubmitterControl)){$sub->cursor[$name]=$control->getValue();}if($control
instanceof
INamingContainer){$cursor=&$sub->cursor[$name];$cursor=array();}}return$values;}public
function
isValid(){if($this->valid===NULL){$this->validate();}return$this->valid;}public
function
validate(){$this->valid=TRUE;foreach($this->getControls()as$control){if(!$control->getRules()->validate()){$this->valid=FALSE;}}}public
function
setCurrentGroup(FormGroup$group=NULL){$this->currentGroup=$group;return$this;}public
function
addComponent(IComponent$component,$name,$insertBefore=NULL){parent::addComponent($component,$name,$insertBefore);if($this->currentGroup!==NULL&&$component
instanceof
IFormControl){$this->currentGroup->add($component);}}public
function
getControls(){return$this->getComponents(TRUE,'Nette\Forms\IFormControl');}public
function
getForm($need=TRUE){return$this->lookup('Nette\Forms\Form',$need);}public
function
addText($name,$label=NULL,$cols=NULL,$maxLength=NULL){return$this[$name]=new
TextInput($label,$cols,$maxLength);}public
function
addPassword($name,$label=NULL,$cols=NULL,$maxLength=NULL){$control=new
TextInput($label,$cols,$maxLength);$control->setPasswordMode(TRUE);$this->addComponent($control,$name);return$control;}public
function
addTextArea($name,$label=NULL,$cols=40,$rows=10){return$this[$name]=new
TextArea($label,$cols,$rows);}public
function
addFile($name,$label=NULL){return$this[$name]=new
FileUpload($label);}public
function
addHidden($name){return$this[$name]=new
HiddenField;}public
function
addCheckbox($name,$caption){return$this[$name]=new
Checkbox($caption);}public
function
addRadioList($name,$label=NULL,array$items=NULL){return$this[$name]=new
RadioList($label,$items);}public
function
addSelect($name,$label=NULL,array$items=NULL,$size=NULL){return$this[$name]=new
SelectBox($label,$items,$size);}public
function
addMultiSelect($name,$label=NULL,array$items=NULL,$size=NULL){return$this[$name]=new
MultiSelectBox($label,$items,$size);}public
function
addSubmit($name,$caption){return$this[$name]=new
SubmitButton($caption);}public
function
addButton($name,$caption){return$this[$name]=new
Button($caption);}public
function
addImage($name,$src=NULL,$alt=NULL){return$this[$name]=new
ImageButton($src,$alt);}public
function
addContainer($name){$control=new
FormContainer;$control->currentGroup=$this->currentGroup;return$this[$name]=$control;}public
function
addRepeater($name){return$this[$name]=new
RepeaterControl;}final
public
function
offsetSet($name,$component){$this->addComponent($component,$name);}final
public
function
offsetGet($name){return$this->getComponent($name,TRUE);}final
public
function
offsetExists($name){return$this->getComponent($name,FALSE)!==NULL;}final
public
function
offsetUnset($name){$component=$this->getComponent($name,FALSE);if($component!==NULL){$this->removeComponent($component);}}final
public
function
__clone(){throw
new
NotImplementedException('Form cloning is not supported yet.');}}class
Form
extends
FormContainer{const
EQUAL=':equal';const
IS_IN=':equal';const
FILLED=':filled';const
VALID=':valid';const
SUBMITTED=':submitted';const
MIN_LENGTH=':minLength';const
MAX_LENGTH=':maxLength';const
LENGTH=':length';const
EMAIL=':email';const
URL=':url';const
REGEXP=':regexp';const
INTEGER=':integer';const
NUMERIC=':integer';const
FLOAT=':float';const
RANGE=':range';const
MAX_FILE_SIZE=':fileSize';const
MIME_TYPE=':mimeType';const
SCRIPT='Nette\Forms\InstantClientScript::javascript';const
GET='get';const
POST='post';const
TRACKER_ID='_form_';const
PROTECTOR_ID='_token_';public$onSubmit;public$onInvalidSubmit;private$submittedBy;private$httpData;private$element;private$renderer;private$translator;private$groups=array();private$errors=array();private$encoding='UTF-8';public
function
__construct($name=NULL){$this->element=Html::el('form');$this->element->action='';$this->element->method=self::POST;$this->monitor(__CLASS__);if($name!==NULL){$tracker=new
HiddenField($name);$tracker->unmonitor(__CLASS__);$this[self::TRACKER_ID]=$tracker;}parent::__construct(NULL,$name);}protected
function
attached($obj){if($obj
instanceof
self){throw
new
InvalidStateException('Nested forms are forbidden.');}}final
public
function
getForm($need=TRUE){return$this;}public
function
setAction($url){$this->element->action=$url;return$this;}public
function
getAction(){return$this->element->action;}public
function
setMethod($method){if($this->httpData!==NULL){throw
new
InvalidStateException(__METHOD__.'() must be called until the form is empty.');}$this->element->method=strtolower($method);return$this;}public
function
getMethod(){return$this->element->method;}public
function
addTracker(){throw
new
DeprecatedException(__METHOD__.'() is deprecated; pass form name to the constructor.');}public
function
addProtection($message=NULL,$timeout=NULL){$session=$this->getSession()->getNamespace('Nette.Forms.Form/CSRF');$key="key$timeout";if(isset($session->$key)){$token=$session->$key;}else{$session->$key=$token=md5(uniqid('',TRUE));}$session->setExpiration($timeout,$key);$this[self::PROTECTOR_ID]=new
HiddenField($token);$this[self::PROTECTOR_ID]->addRule(':equal',empty($message)?'Security token did not match. Possible CSRF attack.':$message,$token);}public
function
addGroup($caption=NULL,$setAsCurrent=TRUE){$group=new
FormGroup;$group->setOption('label',$caption);$group->setOption('visual',TRUE);if($setAsCurrent){$this->setCurrentGroup($group);}if(isset($this->groups[$caption])){return$this->groups[]=$group;}else{return$this->groups[$caption]=$group;}}public
function
removeGroup($name){if(is_string($name)&&isset($this->groups[$name])){$group=$this->groups[$name];}elseif($name
instanceof
FormGroup&&in_array($name,$this->groups,TRUE)){$group=$name;$name=array_search($group,$this->groups,TRUE);}else{throw
new
InvalidArgumentException("Group not found in form '$this->name'");}foreach($group->getControls()as$control){$this->removeComponent($control);}unset($this->groups[$name]);}public
function
getGroups(){return$this->groups;}public
function
getGroup($name){return
isset($this->groups[$name])?$this->groups[$name]:NULL;}public
function
setEncoding($value){$this->encoding=empty($value)?'UTF-8':strtoupper($value);if($this->encoding!=='UTF-8'&&!extension_loaded('mbstring')){throw
new
Exception("The PHP extension 'mbstring' is required for this encoding but is not loaded.");}return$this;}final
public
function
getEncoding(){return$this->encoding;}public
function
setTranslator(ITranslator$translator=NULL){$this->translator=$translator;return$this;}final
public
function
getTranslator(){return$this->translator;}public
function
isAnchored(){return
TRUE;}final
public
function
isSubmitted(){if($this->submittedBy===NULL){$this->getHttpData();$this->submittedBy=!empty($this->httpData);}return$this->submittedBy;}public
function
setSubmittedBy(ISubmitterControl$by=NULL){$this->submittedBy=$by===NULL?FALSE:$by;return$this;}final
public
function
getHttpData(){if($this->httpData===NULL){if(!$this->isAnchored()){throw
new
InvalidStateException('Form is not anchored and therefore can not determine whether it was submitted.');}$this->httpData=(array)$this->receiveHttpData();}return$this->httpData;}public
function
fireEvents(){if(!$this->isSubmitted()){return;}elseif($this->submittedBy
instanceof
ISubmitterControl){if(!$this->submittedBy->getValidationScope()||$this->isValid()){$this->submittedBy->click();$this->onSubmit($this);}else{$this->submittedBy->onInvalidClick($this->submittedBy);$this->onInvalidSubmit($this);}}elseif($this->isValid()){$this->onSubmit($this);}else{$this->onInvalidSubmit($this);}}protected
function
receiveHttpData(){$httpRequest=$this->getHttpRequest();if(strcasecmp($this->getMethod(),$httpRequest->getMethod())){return;}$httpRequest->setEncoding($this->encoding);if($httpRequest->isMethod('post')){$data=ArrayTools::mergeTree($httpRequest->getPost(),$httpRequest->getFiles());}else{$data=$httpRequest->getQuery();}if($tracker=$this->getComponent(self::TRACKER_ID,FALSE)){if(!isset($data[self::TRACKER_ID])||$data[self::TRACKER_ID]!==$tracker->getValue()){return;}}return$data;}public
function
processHttpRequest(){$this->fireEvents();}public
function
getValues(){$values=parent::getValues();unset($values[self::TRACKER_ID],$values[self::PROTECTOR_ID]);return$values;}public
function
addError($message){if(!in_array($message,$this->errors,TRUE)){$this->errors[]=$message;$this->valid=FALSE;}}public
function
getErrors(){return$this->errors;}public
function
hasErrors(){return(bool)$this->getErrors();}public
function
cleanErrors(){$this->errors=array();$this->valid=NULL;}public
function
getElementPrototype(){return$this->element;}public
function
setRenderer(IFormRenderer$renderer){$this->renderer=$renderer;return$this;}final
public
function
getRenderer(){if($this->renderer===NULL){$this->renderer=new
ConventionalRenderer;}return$this->renderer;}public
function
render(){$args=func_get_args();array_unshift($args,$this);$s=call_user_func_array(array($this->getRenderer(),'render'),$args);if(strcmp($this->encoding,'UTF-8')){echo
mb_convert_encoding($s,'HTML-ENTITIES','UTF-8');}else{echo$s;}}public
function
__toString(){try{if(strcmp($this->encoding,'UTF-8')){return
mb_convert_encoding($this->getRenderer()->render($this),'HTML-ENTITIES','UTF-8');}else{return$this->getRenderer()->render($this);}}catch(Exception$e){if(func_get_args()&&func_get_arg(0)){throw$e;}else{trigger_error($e->getMessage(),E_USER_WARNING);return'';}}}protected
function
getHttpRequest(){return
class_exists('Environment')?Environment::getHttpRequest():new
HttpRequest;}protected
function
getSession(){return
Environment::getSession();}}interface
ISignalReceiver{function
signalReceived($signal);}class
AppForm
extends
Form
implements
ISignalReceiver{public
function
__construct(IComponentContainer$parent=NULL,$name=NULL){parent::__construct();$this->monitor('Nette\Application\Presenter');if($parent!==NULL){$parent->addComponent($this,$name);}}public
function
getPresenter($need=TRUE){return$this->lookup('Nette\Application\Presenter',$need);}protected
function
attached($presenter){if($presenter
instanceof
Presenter){$this->setAction(new
Link($presenter,$this->lookupPath('Nette\Application\Presenter').self::NAME_SEPARATOR.'submit!',array()));if($this->isSubmitted()){foreach($this->getControls()as$control){$control->loadHttpData();}}}parent::attached($presenter);}public
function
isAnchored(){return(bool)$this->getPresenter(FALSE);}protected
function
receiveHttpData(){$presenter=$this->getPresenter();if(!$presenter->isSignalReceiver($this,'submit')){return;}$isPost=$this->getMethod()===self::POST;$request=$presenter->getRequest();if($request->isMethod('forward')||$request->isMethod('post')!==$isPost){return;}if($isPost){return
ArrayTools::mergeTree($request->getPost(),$request->getFiles());}else{return$request->getParams();}}public
function
signalReceived($signal){if($signal==='submit'){$this->fireEvents();}else{throw
new
BadSignalException("There is no handler for signal '$signal' in '{$this->getClass()}'.");}}}class
Application
extends
Object{public
static$maxLoop=20;public$defaultServices=array('Nette\Application\IRouter'=>'Nette\Application\MultiRouter','Nette\Application\IPresenterLoader'=>array(__CLASS__,'createPresenterLoader'));public$catchExceptions;public$errorPresenter;public$onStartup;public$onShutdown;public$onRequest;public$onError;public$allowedMethods=array('GET','POST','HEAD','PUT','DELETE');private$requests=array();private$presenter;private$serviceLocator;public
function
run(){$httpRequest=$this->getHttpRequest();$httpResponse=$this->getHttpResponse();$httpRequest->setEncoding('UTF-8');$httpResponse->setHeader('X-Powered-By','Nette Framework');if(Environment::getVariable('baseUri')===NULL){Environment::setVariable('baseUri',$httpRequest->getUri()->getBasePath());}if($this->allowedMethods){$method=$httpRequest->getMethod();if(!in_array($method,$this->allowedMethods,TRUE)){$httpResponse->setCode(IHttpResponse::S501_NOT_IMPLEMENTED);$httpResponse->setHeader('Allow',implode(',',$this->allowedMethods));$method=htmlSpecialChars($method);echo"<h1>Method $method is not implemented</h1>";return;}}$request=NULL;$repeatedError=FALSE;do{try{if(count($this->requests)>self::$maxLoop){throw
new
ApplicationException('Too many loops detected in application life cycle.');}if(!$request){$this->onStartup($this);$router=$this->getRouter();if($router
instanceof
MultiRouter&&!count($router)){$router[]=new
SimpleRouter(array('presenter'=>'Default','action'=>'default'));}$request=$router->match($httpRequest);if(!($request
instanceof
PresenterRequest)){$request=NULL;throw
new
BadRequestException('No route for HTTP request.');}if(strcasecmp($request->getPresenterName(),$this->errorPresenter)===0){throw
new
BadRequestException('Invalid request.');}}$this->requests[]=$request;$this->onRequest($this,$request);$presenter=$request->getPresenterName();try{$class=$this->getPresenterLoader()->getPresenterClass($presenter);$request->setPresenterName($presenter);}catch(InvalidPresenterException$e){throw
new
BadRequestException($e->getMessage(),404,$e);}$request->freeze();$this->presenter=new$class;$response=$this->presenter->run($request);if($response
instanceof
ForwardingResponse){$request=$response->getRequest();continue;}elseif($response
instanceof
IPresenterResponse){$response->send();}break;}catch(Exception$e){if($this->catchExceptions===NULL){$this->catchExceptions=Environment::isProduction();}if(!$this->catchExceptions){throw$e;}$this->onError($this,$e);if($repeatedError){$e=new
ApplicationException('An error occured while executing error-presenter',0,$e);}if(!$httpResponse->isSent()){$httpResponse->setCode($e
instanceof
BadRequestException?$e->getCode():500);}if(!$repeatedError&&$this->errorPresenter){$repeatedError=TRUE;$request=new
PresenterRequest($this->errorPresenter,PresenterRequest::FORWARD,array('exception'=>$e));}else{echo"<meta name='robots' content='noindex'>\n\n";if($e
instanceof
BadRequestException){echo"<title>404 Not Found</title>\n\n<h1>Not Found</h1>\n\n<p>The requested URL was not found on this server.</p>";}else{Debug::processException($e,FALSE);echo"<title>500 Internal Server Error</title>\n\n<h1>Server Error</h1>\n\n","<p>The server encountered an internal error and was unable to complete your request. Please try again later.</p>";}echo"\n\n<hr>\n<small><i>Nette Framework</i></small>";break;}}}while(1);$this->onShutdown($this,isset($e)?$e:NULL);}final
public
function
getRequests(){return$this->requests;}final
public
function
getPresenter(){return$this->presenter;}final
public
function
getServiceLocator(){if($this->serviceLocator===NULL){$this->serviceLocator=new
ServiceLocator(Environment::getServiceLocator());foreach($this->defaultServices
as$name=>$service){if(!$this->serviceLocator->hasService($name)){$this->serviceLocator->addService($name,$service);}}}return$this->serviceLocator;}final
public
function
getService($name,array$options=NULL){return$this->getServiceLocator()->getService($name,$options);}public
function
getRouter(){return$this->getServiceLocator()->getService('Nette\Application\IRouter');}public
function
setRouter(IRouter$router){$this->getServiceLocator()->addService('Nette\Application\IRouter',$router);return$this;}public
function
getPresenterLoader(){return$this->getServiceLocator()->getService('Nette\Application\IPresenterLoader');}public
static
function
createPresenterLoader(){return
new
PresenterLoader(Environment::getVariable('appDir'));}public
function
storeRequest($expiration='+ 10 minutes'){$session=$this->getSession('Nette.Application/requests');do{$key=substr(md5(lcg_value()),0,4);}while(isset($session[$key]));$session[$key]=end($this->requests);$session->setExpiration($expiration,$key);return$key;}public
function
restoreRequest($key){$session=$this->getSession('Nette.Application/requests');if(isset($session[$key])){$request=clone$session[$key];unset($session[$key]);$request->setFlag(PresenterRequest::RESTORED,TRUE);$this->presenter->terminate(new
ForwardingResponse($request));}}protected
function
getHttpRequest(){return
Environment::getHttpRequest();}protected
function
getHttpResponse(){return
Environment::getHttpResponse();}protected
function
getSession($namespace=NULL){return
Environment::getSession($namespace);}}interface
IStatePersistent{function
loadState(array$params);function
saveState(array&$params);}abstract
class
PresenterComponent
extends
ComponentContainer
implements
ISignalReceiver,IStatePersistent,ArrayAccess{protected$params=array();public
function
__construct(IComponentContainer$parent=NULL,$name=NULL){$this->monitor('Nette\Application\Presenter');parent::__construct($parent,$name);}public
function
getPresenter($need=TRUE){return$this->lookup('Nette\Application\Presenter',$need);}public
function
getUniqueId(){return$this->lookupPath('Nette\Application\Presenter',TRUE);}protected
function
attached($presenter){if($presenter
instanceof
Presenter){$this->loadState($presenter->popGlobalParams($this->getUniqueId()));}}protected
function
tryCall($method,array$params){$class=$this->getClass();if(PresenterHelpers::isMethodCallable($class,$method)){$args=PresenterHelpers::paramsToArgs($class,$method,$params);call_user_func_array(array($this,$method),$args);return
TRUE;}return
FALSE;}public
function
loadState(array$params){foreach(PresenterHelpers::getPersistentParams($this->getClass())as$nm=>$meta){if(isset($params[$nm])){if(isset($meta['def'])){settype($params[$nm],gettype($meta['def']));}$this->$nm=&$params[$nm];}}$this->params=$params;}public
function
saveState(array&$params,$forClass=NULL){foreach(PresenterHelpers::getPersistentParams($forClass===NULL?$this->getClass():$forClass)as$nm=>$meta){if(isset($params[$nm])){$val=$params[$nm];}elseif(array_key_exists($nm,$params)){continue;}elseif(!isset($meta['since'])||$this
instanceof$meta['since']){$val=$this->$nm;}else{continue;}if(is_object($val)){throw
new
InvalidStateException("Persistent parameter must be scalar or array, '$this->class::\$$nm' is ".gettype($val));}else{if(isset($meta['def'])){settype($val,gettype($meta['def']));if($val===$meta['def'])$val=NULL;}else{if((string)$val==='')$val=NULL;}$params[$nm]=$val;}}}final
public
function
getParam($name=NULL,$default=NULL){if(func_num_args()===0){return$this->params;}elseif(isset($this->params[$name])){return$this->params[$name];}else{return$default;}}final
public
function
getParamId($name){$uid=$this->getUniqueId();return$uid===''?$name:$uid.self::NAME_SEPARATOR.$name;}public
static
function
getPersistentParams(){$rc=new
ReflectionClass(func_get_arg(0));$params=array();foreach($rc->getProperties()as$rp){if($rp->isPublic()&&!$rp->isStatic()&&Annotations::get($rp,'persistent')){$params[]=$rp->getName();}}return$params;}public
function
signalReceived($signal){if(!$this->tryCall($this->formatSignalMethod($signal),$this->params)){throw
new
BadSignalException("There is no handler for signal '$signal' in '{$this->getClass()}' class.");}}public
function
formatSignalMethod($signal){return$signal==NULL?NULL:'handle'.$signal;}public
function
link($destination,$args=array()){if(!is_array($args)){$args=func_get_args();array_shift($args);}try{return$this->getPresenter()->createRequest($this,$destination,$args,'link');}catch(InvalidLinkException$e){return$this->getPresenter()->handleInvalidLink($e);}}public
function
lazyLink($destination,$args=array()){if(!is_array($args)){$args=func_get_args();array_shift($args);}return
new
Link($this,$destination,$args);}public
function
ajaxLink($destination,$args=array()){throw
new
DeprecatedException(__METHOD__.'() is deprecated.');}public
function
redirect($code,$destination=NULL,$args=array()){if(!is_numeric($code)){$args=$destination;$destination=$code;$code=NULL;}if(!is_array($args)){$args=func_get_args();if(is_numeric(array_shift($args)))array_shift($args);}$presenter=$this->getPresenter();$presenter->redirectUri($presenter->createRequest($this,$destination,$args,'redirect'),$code);}final
public
function
offsetSet($name,$component){$this->addComponent($component,$name);}final
public
function
offsetGet($name){return$this->getComponent($name,TRUE);}final
public
function
offsetExists($name){return$this->getComponent($name,FALSE)!==NULL;}final
public
function
offsetUnset($name){$component=$this->getComponent($name,FALSE);if($component!==NULL){$this->removeComponent($component);}}}interface
IRenderable{function
invalidateControl();function
isControlInvalid();}interface
IPartiallyRenderable
extends
IRenderable{}abstract
class
Control
extends
PresenterComponent
implements
IPartiallyRenderable{private$template;private$invalidSnippets=array();final
public
function
getTemplate(){if($this->template===NULL){$value=$this->createTemplate();if(!($value
instanceof
ITemplate||$value===NULL)){$class=get_class($value);throw
new
UnexpectedValueException("Object returned by $this->class::createTemplate() must be instance of Nette\\Templates\\ITemplate, '$class' given.");}$this->template=$value;}return$this->template;}protected
function
createTemplate(){$template=new
Template;$presenter=$this->getPresenter(FALSE);$template->onPrepareFilters[]=array($this,'templatePrepareFilters');$template->component=$this;$template->control=$this;$template->presenter=$presenter;$template->baseUri=Environment::getVariable('baseUri');$template->basePath=rtrim($template->baseUri,'/');if($presenter!==NULL&&$presenter->hasFlashSession()){$id=$this->getParamId('flash');$template->flashes=$presenter->getFlashSession()->$id;}if(!isset($template->flashes)||!is_array($template->flashes)){$template->flashes=array();}$template->registerHelper('escape','Nette\Templates\TemplateHelpers::escapeHtml');$template->registerHelper('escapeUrl','rawurlencode');$template->registerHelper('stripTags','strip_tags');$template->registerHelper('nl2br','nl2br');$template->registerHelper('substr','iconv_substr');$template->registerHelper('repeat','str_repeat');$template->registerHelper('implode','implode');$template->registerHelper('number','number_format');$template->registerHelperLoader('Nette\Templates\TemplateHelpers::loader');return$template;}public
function
templatePrepareFilters($template){$template->registerFilter(new
LatteFilter);}public
function
getWidget($name){return$this->getComponent($name);}public
function
flashMessage($message,$type='info'){$id=$this->getParamId('flash');$messages=$this->getPresenter()->getFlashSession()->$id;$messages[]=$flash=(object)array('message'=>$message,'type'=>$type);$this->getTemplate()->flashes=$messages;$this->getPresenter()->getFlashSession()->$id=$messages;return$flash;}public
function
invalidateControl($snippet=NULL){$this->invalidSnippets[$snippet]=TRUE;}public
function
validateControl($snippet=NULL){if($snippet===NULL){$this->invalidSnippets=array();}else{unset($this->invalidSnippets[$snippet]);}}public
function
isControlInvalid($snippet=NULL){if($snippet===NULL){if(count($this->invalidSnippets)>0){return
TRUE;}else{foreach($this->getComponents()as$component){if($component
instanceof
IRenderable&&$component->isControlInvalid()){return
TRUE;}}return
FALSE;}}else{return
isset($this->invalidSnippets[NULL])||isset($this->invalidSnippets[$snippet]);}}public
function
getSnippetId($name=NULL){return'snippet-'.$this->getUniqueId().'-'.$name;}}interface
IPresenter{function
run(PresenterRequest$request);}interface
IPresenterLoader{function
getPresenterClass(&$name);}interface
IPresenterResponse{function
send();}interface
IRouter{const
ONE_WAY=1;const
SECURED=2;function
match(IHttpRequest$httpRequest);function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest);}class
Link
extends
Object{private$component;private$destination;private$params;public
function
__construct(PresenterComponent$component,$destination,array$params){$this->component=$component;$this->destination=$destination;$this->params=$params;}public
function
getDestination(){return$this->destination;}public
function
setParam($key,$value){$this->params[$key]=$value;return$this;}public
function
getParam($key){return
isset($this->params[$key])?$this->params[$key]:NULL;}public
function
getParams(){return$this->params;}public
function
__toString(){try{return$this->component->link($this->destination,$this->params);}catch(Exception$e){trigger_error($e->getMessage(),E_USER_WARNING);return'';}}}abstract
class
Presenter
extends
Control
implements
IPresenter{const
PHASE_STARTUP=1;const
PHASE_SIGNAL=3;const
PHASE_RENDER=4;const
PHASE_SHUTDOWN=5;const
INVALID_LINK_SILENT=1;const
INVALID_LINK_WARNING=2;const
INVALID_LINK_EXCEPTION=3;const
SIGNAL_KEY='do';const
ACTION_KEY='action';const
FLASH_KEY='_fid';public
static$defaultAction='default';public
static$invalidLinkMode;public$onShutdown;public$oldLayoutMode=TRUE;public$oldModuleMode=TRUE;private$request;private$response;private$phase;public$autoCanonicalize=TRUE;public$absoluteUrls=FALSE;private$globalParams;private$globalState;private$globalStateSinces;private$action;private$view;private$layout;private$payload;private$signalReceiver;private$signal;private$ajaxMode;private$startupCheck;private$lastCreatedRequest;private$lastCreatedRequestFlag;final
public
function
getRequest(){return$this->request;}final
public
function
getPresenter($need=TRUE){return$this;}final
public
function
getUniqueId(){return'';}public
function
run(PresenterRequest$request){try{$this->phase=self::PHASE_STARTUP;$this->request=$request;$this->payload=(object)NULL;$this->setParent($this->getParent(),$request->getPresenterName());$this->initGlobalParams();$this->startup();if(!$this->startupCheck){$class=$this->reflection->getMethod('startup')->getDeclaringClass()->getName();trigger_error("Method $class::startup() or its descendant doesn't call parent::startup().",E_USER_WARNING);}$this->tryCall($this->formatActionMethod($this->getAction()),$this->params);if($this->autoCanonicalize){$this->canonicalize();}if($this->getHttpRequest()->isMethod('head')){$this->terminate();}if(method_exists($this,'beforePrepare')){$this->beforePrepare();trigger_error('beforePrepare() is deprecated; use createComponent{Name}() instead.',E_USER_WARNING);}if($this->tryCall('prepare'.$this->getView(),$this->params)){trigger_error('prepare'.ucfirst($this->getView()).'() is deprecated; use createComponent{Name}() instead.',E_USER_WARNING);}$this->phase=self::PHASE_SIGNAL;$this->processSignal();$this->phase=self::PHASE_RENDER;$this->beforeRender();$this->tryCall($this->formatRenderMethod($this->getView()),$this->params);$this->afterRender();$this->saveGlobalState();if($this->isAjax()){$this->payload->state=$this->getGlobalState();}$this->sendTemplate();}catch(AbortException$e){}{$this->phase=self::PHASE_SHUTDOWN;if($this->isAjax())try{$hasPayload=count((array)$this->payload)>1;if($this->response
instanceof
RenderResponse&&($this->isControlInvalid()||$hasPayload)){SnippetHelper::$outputAllowed=FALSE;$this->response->send();$this->sendPayload();}elseif(!$this->response&&$hasPayload){$this->sendPayload();}}catch(AbortException$e){}if($this->hasFlashSession()){$this->getFlashSession()->setExpiration($this->response
instanceof
RedirectingResponse?'+ 30 seconds':'+ 3 seconds');}$this->onShutdown($this,$this->response);$this->shutdown($this->response);return$this->response;}}final
public
function
getPhase(){return$this->phase;}protected
function
startup(){$this->startupCheck=TRUE;}protected
function
beforeRender(){}protected
function
afterRender(){}protected
function
shutdown($response){}public
function
processSignal(){if($this->signal===NULL)return;$component=$this->signalReceiver===''?$this:$this->getComponent($this->signalReceiver,FALSE);if($component===NULL){throw
new
BadSignalException("The signal receiver component '$this->signalReceiver' is not found.");}elseif(!$component
instanceof
ISignalReceiver){throw
new
BadSignalException("The signal receiver component '$this->signalReceiver' is not ISignalReceiver implementor.");}if($this->oldLayoutMode&&$component
instanceof
IRenderable){$component->invalidateControl();}$component->signalReceived($this->signal);$this->signal=NULL;}final
public
function
getSignal(){return$this->signal===NULL?NULL:array($this->signalReceiver,$this->signal);}final
public
function
isSignalReceiver($component,$signal=NULL){if($component
instanceof
Component){$component=$component===$this?'':$component->lookupPath(__CLASS__,TRUE);}if($this->signal===NULL){return
FALSE;}elseif($signal===TRUE){return$component===''||strncmp($this->signalReceiver.'-',$component.'-',strlen($component)+1)===0;}elseif($signal===NULL){return$this->signalReceiver===$component;}else{return$this->signalReceiver===$component&&strcasecmp($signal,$this->signal)===0;}}final
public
function
getAction($fullyQualified=FALSE){return$fullyQualified?':'.$this->getName().':'.$this->action:$this->action;}public
function
changeAction($action){if(preg_match("#^[a-zA-Z0-9][a-zA-Z0-9_\x7f-\xff]*$#",$action)){$this->action=$action;$this->view=$action;}else{throw
new
BadRequestException("Action name '$action' is not alphanumeric string.");}}final
public
function
getView(){return$this->view;}public
function
setView($view){$this->view=(string)$view;return$this;}final
public
function
getLayout(){return$this->layout;}public
function
setLayout($layout){$this->layout=$layout===FALSE?FALSE:(string)$layout;return$this;}public
function
sendTemplate(){$template=$this->getTemplate();if(!$template)return;if($template
instanceof
IFileTemplate&&!$template->getFile()){$files=$this->formatTemplateFiles($this->getName(),$this->view);foreach($files
as$file){if(is_file($file)){$template->setFile($file);break;}}if(!$template->getFile()){$file=str_replace(Environment::getVariable('appDir'),"\xE2\x80\xA6",reset($files));throw
new
BadRequestException("Page not found. Missing template '$file'.");}if($this->layout!==FALSE){$files=$this->formatLayoutTemplateFiles($this->getName(),$this->layout?$this->layout:'layout');foreach($files
as$file){if(is_file($file)){$template->layout=$file;if($this->oldLayoutMode){$template->content=clone$template;$template->setFile($file);}else{$template->_extends=$file;}break;}}if(empty($template->layout)&&$this->layout!==NULL){$file=str_replace(Environment::getVariable('appDir'),"\xE2\x80\xA6",reset($files));throw
new
FileNotFoundException("Layout not found. Missing template '$file'.");}}}$this->terminate(new
RenderResponse($template));}public
function
formatLayoutTemplateFiles($presenter,$layout){if($this->oldModuleMode){$root=Environment::getVariable('templatesDir',Environment::getVariable('appDir').'/templates');$presenter=str_replace(':','Module/',$presenter);$module=substr($presenter,0,(int)strrpos($presenter,'/'));$base='';if($root===Environment::getVariable('appDir').'/presenters'){$base='templates/';if($module===''){$presenter='templates/'.$presenter;}else{$presenter=substr_replace($presenter,'/templates',strrpos($presenter,'/'),0);}}return
array("$root/$presenter/@$layout.phtml","$root/$presenter.@$layout.phtml","$root/$module/$base@$layout.phtml","$root/$base@$layout.phtml");}$appDir=Environment::getVariable('appDir');$path='/'.str_replace(':','Module/',$presenter);$pathP=substr_replace($path,'/templates',strrpos($path,'/'),0);$list=array("$appDir$pathP/@$layout.phtml","$appDir$pathP.@$layout.phtml");while(($path=substr($path,0,strrpos($path,'/')))!==FALSE){$list[]="$appDir$path/templates/@$layout.phtml";}return$list;}public
function
formatTemplateFiles($presenter,$view){if($this->oldModuleMode){$root=Environment::getVariable('templatesDir',Environment::getVariable('appDir').'/templates');$presenter=str_replace(':','Module/',$presenter);$dir='';if($root===Environment::getVariable('appDir').'/presenters'){$pos=strrpos($presenter,'/');$presenter=$pos===FALSE?'templates/'.$presenter:substr_replace($presenter,'/templates',$pos,0);$dir='templates/';}return
array("$root/$presenter/$view.phtml","$root/$presenter.$view.phtml","$root/$dir@global.$view.phtml");}$appDir=Environment::getVariable('appDir');$path='/'.str_replace(':','Module/',$presenter);$pathP=substr_replace($path,'/templates',strrpos($path,'/'),0);$path=substr_replace($path,'/templates',strrpos($path,'/'));return
array("$appDir$pathP/$view.phtml","$appDir$pathP.$view.phtml","$appDir$path/@global.$view.phtml");}protected
static
function
formatActionMethod($action){return'action'.$action;}protected
static
function
formatRenderMethod($view){return'render'.$view;}protected
function
renderTemplate(){throw
new
DeprecatedException(__METHOD__.'() is deprecated; use $presenter->sendTemplate() instead.');}final
public
function
getPayload(){return$this->payload;}public
function
isAjax(){if($this->ajaxMode===NULL){$this->ajaxMode=$this->getHttpRequest()->isAjax();}return$this->ajaxMode;}protected
function
sendPayload(){$this->terminate(new
JsonResponse($this->payload));}public
function
getAjaxDriver(){throw
new
DeprecatedException(__METHOD__.'() is deprecated; use $presenter->payload instead.');}public
function
forward($destination,$args=array()){if($destination
instanceof
PresenterRequest){$this->terminate(new
ForwardingResponse($destination));}elseif(!is_array($args)){$args=func_get_args();array_shift($args);}$this->createRequest($this,$destination,$args,'forward');$this->terminate(new
ForwardingResponse($this->lastCreatedRequest));}public
function
redirectUri($uri,$code=NULL){if($this->isAjax()){$this->payload->redirect=(string)$uri;$this->sendPayload();}elseif(!$code){$code=$this->getHttpRequest()->isMethod('post')?IHttpResponse::S303_POST_GET:IHttpResponse::S302_FOUND;}$this->terminate(new
RedirectingResponse($uri,$code));}public
function
backlink(){return$this->getAction(TRUE);}public
function
getLastCreatedRequest(){return$this->lastCreatedRequest;}public
function
getLastCreatedRequestFlag($flag){return!empty($this->lastCreatedRequestFlag[$flag]);}public
function
terminate(IPresenterResponse$response=NULL){$this->response=$response;throw
new
AbortException();}public
function
canonicalize(){if(!$this->isAjax()&&($this->request->isMethod('get')||$this->request->isMethod('head'))){$uri=$this->createRequest($this,$this->action,$this->getGlobalState()+$this->request->params,'redirectX');if($uri!==NULL&&!$this->getHttpRequest()->getUri()->isEqual($uri)){$this->terminate(new
RedirectingResponse($uri,IHttpResponse::S301_MOVED_PERMANENTLY));}}}public
function
lastModified($lastModified,$etag=NULL,$expire=NULL){if(!Environment::isProduction()){return
NULL;}$httpResponse=$this->getHttpResponse();$match=FALSE;if($lastModified>0){$httpResponse->setHeader('Last-Modified',HttpResponse::date($lastModified));}if($etag!=NULL){$etag='"'.addslashes($etag).'"';$httpResponse->setHeader('ETag',$etag);}if($expire!==NULL){$httpResponse->expire($expire);}$ifNoneMatch=$this->getHttpRequest()->getHeader('if-none-match');$ifModifiedSince=$this->getHttpRequest()->getHeader('if-modified-since');if($ifModifiedSince!==NULL){$ifModifiedSince=strtotime($ifModifiedSince);}if($ifNoneMatch!==NULL){if($ifNoneMatch==='*'){$match=TRUE;}elseif($etag==NULL||strpos(' '.strtr($ifNoneMatch,",\t",'  '),' '.$etag)===FALSE){return$ifModifiedSince;}else{$match=TRUE;}}if($ifModifiedSince!==NULL){if($lastModified>0&&$lastModified<=$ifModifiedSince){$match=TRUE;}else{return$ifModifiedSince;}}if($match){$httpResponse->setCode(IHttpResponse::S304_NOT_MODIFIED);$httpResponse->setHeader('Content-Length','0');$this->terminate();}else{return$ifModifiedSince;}return
NULL;}final
protected
function
createRequest($component,$destination,array$args,$mode){static$presenterLoader,$router,$httpRequest;if($presenterLoader===NULL){$presenterLoader=$this->getApplication()->getPresenterLoader();$router=$this->getApplication()->getRouter();$httpRequest=$this->getHttpRequest();}$this->lastCreatedRequest=$this->lastCreatedRequestFlag=NULL;$a=strpos($destination,'#');if($a===FALSE){$fragment='';}else{$fragment=substr($destination,$a);$destination=substr($destination,0,$a);}$a=strpos($destination,'?');if($a!==FALSE){parse_str(substr($destination,$a+1),$args);$destination=substr($destination,0,$a);}$a=strpos($destination,'//');if($a===FALSE){$scheme=FALSE;}else{$scheme=substr($destination,0,$a);$destination=substr($destination,$a+2);}if(!($component
instanceof
Presenter)||substr($destination,-1)==='!'){$signal=rtrim($destination,'!');$a=strrpos($signal,':');if($a!==FALSE){$component=$component->getComponent(strtr(substr($signal,0,$a),':','-'));$signal=(string)substr($signal,$a+1);}if($signal==NULL){throw
new
InvalidLinkException("Signal must be non-empty string.");}$destination='this';}if($destination==NULL){throw
new
InvalidLinkException("Destination must be non-empty string.");}$current=FALSE;$a=strrpos($destination,':');if($a===FALSE){$action=$destination==='this'?$this->action:$destination;$presenter=$this->getName();$presenterClass=$this->getClass();}else{$action=(string)substr($destination,$a+1);if($destination[0]===':'){if($a<2){throw
new
InvalidLinkException("Missing presenter name in '$destination'.");}$presenter=substr($destination,1,$a-1);}else{$presenter=$this->getName();$b=strrpos($presenter,':');if($b===FALSE){$presenter=substr($destination,0,$a);}else{$presenter=substr($presenter,0,$b+1).substr($destination,0,$a);}}$presenterClass=$presenterLoader->getPresenterClass($presenter);}if(isset($signal)){$class=get_class($component);if($signal==='this'){$signal='';if(array_key_exists(0,$args)){throw
new
InvalidLinkException("Extra parameter for signal '$class:$signal!'.");}}elseif(strpos($signal,self::NAME_SEPARATOR)===FALSE){$method=$component->formatSignalMethod($signal);if(!PresenterHelpers::isMethodCallable($class,$method)){throw
new
InvalidLinkException("Unknown signal '$class:$signal!'.");}if($args){PresenterHelpers::argsToParams($class,$method,$args);}}if($args&&array_intersect_key($args,PresenterHelpers::getPersistentParams($class))){$component->saveState($args);}if($args&&$component!==$this){$prefix=$component->getUniqueId().self::NAME_SEPARATOR;foreach($args
as$key=>$val){unset($args[$key]);$args[$prefix.$key]=$val;}}}if(is_subclass_of($presenterClass,__CLASS__)){if($action===''){$action=self::$defaultAction;}$current=($action==='*'||$action===$this->action)&&$presenterClass===$this->getClass();if($args||$destination==='this'){$method=call_user_func(array($presenterClass,'formatActionMethod'),$action);if(!PresenterHelpers::isMethodCallable($presenterClass,$method)){$method=call_user_func(array($presenterClass,'formatRenderMethod'),$action);if(!PresenterHelpers::isMethodCallable($presenterClass,$method)){$method=NULL;}}if($method===NULL){if(array_key_exists(0,$args)){throw
new
InvalidLinkException("Extra parameter for '$presenter:$action'.");}}elseif($destination==='this'){PresenterHelpers::argsToParams($presenterClass,$method,$args,$this->params);}else{PresenterHelpers::argsToParams($presenterClass,$method,$args);}}if($args&&array_intersect_key($args,PresenterHelpers::getPersistentParams($presenterClass))){$this->saveState($args,$presenterClass);}$globalState=$this->getGlobalState($destination==='this'?NULL:$presenterClass);if($current&&$args){$tmp=$globalState+$this->params;foreach($args
as$key=>$val){if((string)$val!==(isset($tmp[$key])?(string)$tmp[$key]:'')){$current=FALSE;break;}}}$args+=$globalState;}$args[self::ACTION_KEY]=$action;if(!empty($signal)){$args[self::SIGNAL_KEY]=$component->getParamId($signal);$current=$current&&$args[self::SIGNAL_KEY]===$this->getParam(self::SIGNAL_KEY);}if(($mode==='redirect'||$mode==='forward')&&$this->hasFlashSession()){$args[self::FLASH_KEY]=$this->getParam(self::FLASH_KEY);}$this->lastCreatedRequest=new
PresenterRequest($presenter,PresenterRequest::FORWARD,$args,array(),array());$this->lastCreatedRequestFlag=array('current'=>$current);if($mode==='forward')return;$uri=$router->constructUrl($this->lastCreatedRequest,$httpRequest);if($uri===NULL){unset($args[self::ACTION_KEY]);$params=urldecode(http_build_query($args,NULL,', '));throw
new
InvalidLinkException("No route for $presenter:$action($params)");}if($mode==='link'&&$scheme===FALSE&&!$this->absoluteUrls){$hostUri=$httpRequest->getUri()->getHostUri();if(strncmp($uri,$hostUri,strlen($hostUri))===0){$uri=substr($uri,strlen($hostUri));}}return$uri.$fragment;}protected
function
handleInvalidLink($e){if(self::$invalidLinkMode===NULL){self::$invalidLinkMode=Environment::isProduction()?self::INVALID_LINK_SILENT:self::INVALID_LINK_WARNING;}if(self::$invalidLinkMode===self::INVALID_LINK_SILENT){return'#';}elseif(self::$invalidLinkMode===self::INVALID_LINK_WARNING){return'error: '.htmlSpecialChars($e->getMessage());}else{throw$e;}}public
static
function
getPersistentComponents(){return(array)Annotations::get(new
ReflectionClass(func_get_arg(0)),'persistent');}private
function
getGlobalState($forClass=NULL){$sinces=&$this->globalStateSinces;if($this->globalState===NULL){if($this->phase>=self::PHASE_SHUTDOWN){throw
new
InvalidStateException("Presenter is shutting down, cannot save state.");}$state=array();foreach($this->globalParams
as$id=>$params){$prefix=$id.self::NAME_SEPARATOR;foreach($params
as$key=>$val){$state[$prefix.$key]=$val;}}$this->saveState($state,$forClass);if($sinces===NULL){$sinces=array();foreach(PresenterHelpers::getPersistentParams(get_class($this))as$nm=>$meta){$sinces[$nm]=$meta['since'];}}$components=PresenterHelpers::getPersistentComponents(get_class($this));$iterator=$this->getComponents(TRUE,'Nette\Application\IStatePersistent');foreach($iterator
as$name=>$component){if($iterator->getDepth()===0){$since=isset($components[$name]['since'])?$components[$name]['since']:FALSE;}$prefix=$component->getUniqueId().self::NAME_SEPARATOR;$params=array();$component->saveState($params);foreach($params
as$key=>$val){$state[$prefix.$key]=$val;$sinces[$prefix.$key]=$since;}}}else{$state=$this->globalState;}if($forClass!==NULL){$since=NULL;foreach($state
as$key=>$foo){if(!isset($sinces[$key])){$x=strpos($key,self::NAME_SEPARATOR);$x=$x===FALSE?$key:substr($key,0,$x);$sinces[$key]=isset($sinces[$x])?$sinces[$x]:FALSE;}if($since!==$sinces[$key]){$since=$sinces[$key];$ok=$since&&(is_subclass_of($forClass,$since)||$forClass===$since);}if(!$ok){unset($state[$key]);}}}return$state;}protected
function
saveGlobalState(){foreach($this->globalParams
as$id=>$foo){$this->getComponent($id,FALSE);}$this->globalParams=array();$this->globalState=$this->getGlobalState();}private
function
initGlobalParams(){$this->globalParams=array();$selfParams=array();$params=$this->request->getParams();if($this->isAjax()){$params=$this->request->getPost()+$params;}foreach($params
as$key=>$value){$a=strlen($key)>2?strrpos($key,self::NAME_SEPARATOR,-2):FALSE;if($a===FALSE){$selfParams[$key]=$value;}else{$this->globalParams[substr($key,0,$a)][substr($key,$a+1)]=$value;}}$this->changeAction(isset($selfParams[self::ACTION_KEY])?$selfParams[self::ACTION_KEY]:self::$defaultAction);$this->signalReceiver=$this->getUniqueId();if(!empty($selfParams[self::SIGNAL_KEY])){$param=$selfParams[self::SIGNAL_KEY];$pos=strrpos($param,'-');if($pos){$this->signalReceiver=substr($param,0,$pos);$this->signal=substr($param,$pos+1);}else{$this->signalReceiver=$this->getUniqueId();$this->signal=$param;}if($this->signal==NULL){$this->signal=NULL;}}$this->loadState($selfParams);}final
public
function
popGlobalParams($id){if(isset($this->globalParams[$id])){$res=$this->globalParams[$id];unset($this->globalParams[$id]);return$res;}else{return
array();}}public
function
hasFlashSession(){return!empty($this->params[self::FLASH_KEY])&&$this->getSession()->hasNamespace('Nette.Application.Flash/'.$this->params[self::FLASH_KEY]);}public
function
getFlashSession(){if(empty($this->params[self::FLASH_KEY])){$this->params[self::FLASH_KEY]=substr(md5(lcg_value()),0,4);}return$this->getSession('Nette.Application.Flash/'.$this->params[self::FLASH_KEY]);}protected
function
getHttpRequest(){return
Environment::getHttpRequest();}protected
function
getHttpResponse(){return
Environment::getHttpResponse();}public
function
getApplication(){return
Environment::getApplication();}protected
function
getSession($namespace=NULL){return
Environment::getSession($namespace);}protected
function
getUser(){return
Environment::getUser();}}final
class
PresenterHelpers{private
static$ppCache=array();private
static$pcCache=array();private
static$mcCache=array();private
static$mpCache=array();final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
getPersistentParams($class){$params=&self::$ppCache[$class];if($params!==NULL)return$params;$params=array();if(is_subclass_of($class,'PresenterComponent')){$defaults=get_class_vars($class);foreach(call_user_func(array($class,'getPersistentParams'),$class)as$name=>$meta){if(is_string($meta))$name=$meta;$params[$name]=array('def'=>$defaults[$name],'since'=>$class);}$params=self::getPersistentParams(get_parent_class($class))+$params;}return$params;}public
static
function
getPersistentComponents($class){$components=&self::$pcCache[$class];if($components!==NULL)return$components;$components=array();if(is_subclass_of($class,'Presenter')){foreach(call_user_func(array($class,'getPersistentComponents'),$class)as$name=>$meta){if(is_string($meta))$name=$meta;$components[$name]=array('since'=>$class);}$components=self::getPersistentComponents(get_parent_class($class))+$components;}return$components;}public
static
function
isMethodCallable($class,$method){$cache=&self::$mcCache[strtolower($class.':'.$method)];if($cache!==NULL)return$cache;try{$cache=FALSE;$rc=new
ReflectionClass($class);if(!$rc->isInstantiable()){return
FALSE;}$rm=$rc->getMethod($method);if(!$rm||!$rm->isPublic()||$rm->isAbstract()||$rm->isStatic()){return
FALSE;}return$cache=TRUE;}catch(ReflectionException$e){return
FALSE;}}public
static
function
paramsToArgs($class,$method,$params){$args=array();$i=0;foreach(self::getMethodParams($class,$method)as$name=>$def){if(isset($params[$name])){$val=$params[$name];if($def!==NULL){settype($val,gettype($def));}$args[$i++]=$val;}else{$args[$i++]=$def;}}return$args;}public
static
function
argsToParams($class,$method,&$args,$supplemental=array()){$i=0;foreach(self::getMethodParams($class,$method)as$name=>$def){if(array_key_exists($i,$args)){$args[$name]=$args[$i];unset($args[$i]);$i++;}elseif(array_key_exists($name,$args)){}elseif(array_key_exists($name,$supplemental)){$args[$name]=$supplemental[$name];}else{continue;}if($def===NULL){if((string)$args[$name]==='')$args[$name]=NULL;}else{settype($args[$name],gettype($def));if($args[$name]===$def)$args[$name]=NULL;}}if(array_key_exists($i,$args)){throw
new
InvalidLinkException("Extra parameter for signal '$class:$method'.");}}private
static
function
getMethodParams($class,$method){$cache=&self::$mpCache[strtolower($class.':'.$method)];if($cache!==NULL)return$cache;$rm=new
ReflectionMethod($class,$method);$cache=array();foreach($rm->getParameters()as$param){$cache[$param->getName()]=$param->isDefaultValueAvailable()?$param->getDefaultValue():NULL;}return$cache;}}class
PresenterLoader
implements
IPresenterLoader{public$caseSensitive=FALSE;private$baseDir;private$cache=array();public
function
__construct($baseDir){$this->baseDir=$baseDir;}public
function
getPresenterClass(&$name){if(isset($this->cache[$name])){list($class,$name)=$this->cache[$name];return$class;}if(!is_string($name)||!preg_match("#^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff:]*$#",$name)){throw
new
InvalidPresenterException("Presenter name must be alphanumeric string, '$name' is invalid.");}$class=$this->formatPresenterClass($name);if(!class_exists($class)){$file=$this->formatPresenterFile($name);if(is_file($file)&&is_readable($file)){LimitedScope::load($file);}if(!class_exists($class)){throw
new
InvalidPresenterException("Cannot load presenter '$name', class '$class' was not found in '$file'.");}}$reflection=new
ReflectionClass($class);$class=$reflection->getName();if(!$reflection->implementsInterface('IPresenter')){throw
new
InvalidPresenterException("Cannot load presenter '$name', class '$class' is not Nette\\Application\\IPresenter implementor.");}if($reflection->isAbstract()){throw
new
InvalidPresenterException("Cannot load presenter '$name', class '$class' is abstract.");}$realName=$this->unformatPresenterClass($class);if($name!==$realName){if($this->caseSensitive){throw
new
InvalidPresenterException("Cannot load presenter '$name', case mismatch. Real name is '$realName'.");}else{$this->cache[$name]=array($class,$realName);$name=$realName;}}else{$this->cache[$name]=array($class,$realName);}return$class;}public
function
formatPresenterClass($presenter){return
strtr($presenter,':','_').'Presenter';}public
function
unformatPresenterClass($class){return
strtr(substr($class,0,-9),'_',':');}public
function
formatPresenterFile($presenter){$path='/'.str_replace(':','Module/',$presenter);return$this->baseDir.substr_replace($path,'/presenters',strrpos($path,'/'),0).'Presenter.php';}}final
class
PresenterRequest
extends
FreezableObject{const
FORWARD='FORWARD';const
SECURED='secured';const
RESTORED='restored';private$method;private$flags=array();private$name;private$params;private$post;private$files;public
function
__construct($name,$method,array$params,array$post=array(),array$files=array(),array$flags=array()){$this->name=$name;$this->method=$method;$this->params=$params;$this->post=$post;$this->files=$files;$this->flags=$flags;}public
function
setPresenterName($name){$this->updating();$this->name=$name;return$this;}public
function
getPresenterName(){return$this->name;}public
function
setParams(array$params){$this->updating();$this->params=$params;return$this;}public
function
getParams(){return$this->params;}public
function
setPost(array$params){$this->updating();$this->post=$params;return$this;}public
function
getPost(){return$this->post;}public
function
setFiles(array$files){$this->updating();$this->files=$files;return$this;}public
function
getFiles(){return$this->files;}public
function
setMethod($method){$this->method=$method;return$this;}public
function
getMethod(){return$this->method;}public
function
isMethod($method){return
strcasecmp($this->method,$method)===0;}public
function
isPost(){return
strcasecmp($this->method,'post')===0;}public
function
setFlag($flag,$value=TRUE){$this->updating();$this->flags[$flag]=(bool)$value;return$this;}public
function
hasFlag($flag){return!empty($this->flags[$flag]);}}class
AbortException
extends
Exception{}class
ApplicationException
extends
Exception{function
__construct($message='',$code=0,Exception$previous=NULL){if(version_compare(PHP_VERSION,'5.3','<')){$this->previous=$previous;parent::__construct($message,$code);}else{parent::__construct($message,$code,$previous);}}}class
BadRequestException
extends
Exception{protected$defaultCode=404;function
__construct($message='',$code=0,Exception$previous=NULL){if($code<200||$code>504){$code=$this->defaultCode;}if(version_compare(PHP_VERSION,'5.3','<')){$this->previous=$previous;parent::__construct($message,$code);}else{parent::__construct($message,$code,$previous);}}}class
BadSignalException
extends
BadRequestException{protected$defaultCode=403;}class
InvalidLinkException
extends
Exception{}class
InvalidPresenterException
extends
InvalidLinkException{}class
DownloadResponse
extends
Object
implements
IPresenterResponse{private$file;private$contentType;private$name;public
function
__construct($file,$name=NULL,$contentType=NULL){if(!is_file($file)){throw
new
BadRequestException("File '$file' doesn't exist.");}$this->file=$file;$this->name=$name?$name:basename($file);$this->contentType=$contentType?$contentType:'application/octet-stream';}final
public
function
getFile(){return$this->file;}final
public
function
getName(){return$this->name;}final
public
function
getContentType(){return$this->contentType;}public
function
send(){Environment::getHttpResponse()->setContentType($this->contentType);Environment::getHttpResponse()->setHeader('Content-Disposition','attachment; filename="'.$this->name.'"');readfile($this->file);}}class
ForwardingResponse
extends
Object
implements
IPresenterResponse{private$request;public
function
__construct(PresenterRequest$request){$this->request=$request;}final
public
function
getRequest(){return$this->request;}public
function
send(){}}class
JsonResponse
extends
Object
implements
IPresenterResponse{private$payload;private$contentType;public
function
__construct($payload,$contentType=NULL){if(!is_array($payload)&&!($payload
instanceof
stdClass)){throw
new
InvalidArgumentException("Payload must be array or anonymous class, ".gettype($payload)." given.");}$this->payload=$payload;$this->contentType=$contentType?$contentType:'application/json';}final
public
function
getPayload(){return$this->payload;}public
function
send(){Environment::getHttpResponse()->setContentType($this->contentType);Environment::getHttpResponse()->expire(FALSE);echo
json_encode($this->payload);}}class
RedirectingResponse
extends
Object
implements
IPresenterResponse{private$uri;private$code;public
function
__construct($uri,$code=IHttpResponse::S302_FOUND){$this->uri=(string)$uri;$this->code=(int)$code;}final
public
function
getUri(){return$this->uri;}final
public
function
getCode(){return$this->code;}public
function
send(){Environment::getHttpResponse()->redirect($this->uri,$this->code);}}class
RenderResponse
extends
Object
implements
IPresenterResponse{private$source;public
function
__construct($source){$this->source=$source;}final
public
function
getSource(){return$this->source;}public
function
send(){if($this->source
instanceof
ITemplate){$this->source->render();}else{echo$this->source;}}}class
CliRouter
extends
Object
implements
IRouter{const
PRESENTER_KEY='action';private$defaults;public
function
__construct($defaults=array()){$this->defaults=$defaults;}public
function
match(IHttpRequest$httpRequest){if(empty($_SERVER['argv'])||!is_array($_SERVER['argv'])){return
NULL;}$names=array(self::PRESENTER_KEY);$params=$this->defaults;$args=$_SERVER['argv'];array_shift($args);$args[]='--';foreach($args
as$arg){$opt=preg_replace('#/|-+#A','',$arg);if($opt===$arg){if(isset($flag)||$flag=array_shift($names)){$params[$flag]=$arg;}else{$params[]=$arg;}$flag=NULL;continue;}if(isset($flag)){$params[$flag]=TRUE;$flag=NULL;}if($opt!==''){$pair=explode('=',$opt,2);if(isset($pair[1])){$params[$pair[0]]=$pair[1];}else{$flag=$pair[0];}}}if(!isset($params[self::PRESENTER_KEY])){throw
new
InvalidStateException('Missing presenter & action in route definition.');}$presenter=$params[self::PRESENTER_KEY];if($a=strrpos($presenter,':')){$params[self::PRESENTER_KEY]=substr($presenter,$a+1);$presenter=substr($presenter,0,$a);}return
new
PresenterRequest($presenter,'CLI',$params);}public
function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){return
NULL;}public
function
getDefaults(){return$this->defaults;}}interface
ICollection
extends
Countable,IteratorAggregate{function
append($item);function
remove($item);function
clear();function
contains($item);}abstract
class
Collection
extends
ArrayObject
implements
ICollection{private$itemType;private$checkFunc;private$frozen=FALSE;public
function
__construct($arr=NULL,$type=NULL){if(substr($type,0,1)===':'){$this->itemType=substr($type,1);$this->checkFunc='is_'.$this->itemType;}else{$this->itemType=$type;}if($arr!==NULL){$this->import($arr);}}public
function
append($item){$this->beforeAdd($item);parent::append($item);}public
function
remove($item){$this->updating();$index=$this->search($item);if($index===FALSE){return
FALSE;}else{parent::offsetUnset($index);return
TRUE;}}protected
function
search($item){return
array_search($item,$this->getArrayCopy(),TRUE);}public
function
clear(){$this->updating();parent::exchangeArray(array());}public
function
contains($item){return$this->search($item)!==FALSE;}public
function
import($arr){if(!(is_array($arr)||$arr
instanceof
Traversable)){throw
new
InvalidArgumentException("Argument must be traversable.");}$this->clear();foreach($arr
as$item){$this->offsetSet(NULL,$item);}}public
function
getItemType(){return$this->itemType;}public
function
setReadOnly(){throw
new
DeprecatedException(__METHOD__.'() is deprecated; use freeze() instead.');}public
function
isReadOnly(){throw
new
DeprecatedException(__METHOD__.'() is deprecated; use isFrozen() instead.');}protected
function
beforeAdd($item){$this->updating();if($this->itemType!==NULL){if($this->checkFunc===NULL){if(!($item
instanceof$this->itemType)){throw
new
InvalidArgumentException("Item must be '$this->itemType' object.");}}else{$fnc=$this->checkFunc;if(!$fnc($item)){throw
new
InvalidArgumentException("Item must be $this->itemType type.");}}}}public
function
getIterator(){return
new
ArrayIterator($this->getArrayCopy());}public
function
exchangeArray($array){throw
new
NotSupportedException('Use '.__CLASS__.'::import()');}protected
function
setArray($array){parent::exchangeArray($array);return$this;}final
public
function
getClass(){return
get_class($this);}public
function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}public
static
function
__callStatic($name,$args){$class=get_called_class();throw
new
MemberAccessException("Call to undefined static method $class::$name().");}public
function&__get($name){return
ObjectMixin::get($this,$name);}public
function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}public
function
__isset($name){return
ObjectMixin::has($this,$name);}public
function
__unset($name){throw
new
MemberAccessException("Cannot unset the property $this->class::\$$name.");}public
function
freeze(){$this->frozen=TRUE;}final
public
function
isFrozen(){return$this->frozen;}public
function
__clone(){$this->frozen=FALSE;}protected
function
updating(){if($this->frozen){$class=get_class($this);throw
new
InvalidStateException("Cannot modify a frozen object '$class'.");}}}interface
IList
extends
ICollection,ArrayAccess{function
indexOf($item);function
insertAt($index,$item);}class
ArrayList
extends
Collection
implements
IList{protected$base=0;public
function
insertAt($index,$item){$index-=$this->base;if($index<0||$index>count($this)){throw
new
ArgumentOutOfRangeException;}$this->beforeAdd($item);$data=$this->getArrayCopy();array_splice($data,(int)$index,0,array($item));$this->setArray($data);return
TRUE;}public
function
remove($item){$this->updating();$index=$this->search($item);if($index===FALSE){return
FALSE;}else{$data=$this->getArrayCopy();array_splice($data,$index,1);$this->setArray($data);return
TRUE;}}public
function
indexOf($item){$index=$this->search($item);return$index===FALSE?FALSE:$this->base+$index;}public
function
offsetSet($index,$item){$this->beforeAdd($item);if($index===NULL){parent::offsetSet(NULL,$item);}else{$index-=$this->base;if($index<0||$index>=count($this)){throw
new
ArgumentOutOfRangeException;}parent::offsetSet($index,$item);}}public
function
offsetGet($index){$index-=$this->base;if($index<0||$index>=count($this)){throw
new
ArgumentOutOfRangeException;}return
parent::offsetGet($index);}public
function
offsetExists($index){$index-=$this->base;return$index>=0&&$index<count($this);}public
function
offsetUnset($index){$this->updating();$index-=$this->base;if($index<0||$index>=count($this)){throw
new
ArgumentOutOfRangeException;}$data=$this->getArrayCopy();array_splice($data,(int)$index,1);$this->setArray($data);}}class
MultiRouter
extends
ArrayList
implements
IRouter{private$cachedRoutes;public
function
__construct(){parent::__construct(NULL,'IRouter');}public
function
match(IHttpRequest$httpRequest){foreach($this
as$route){$appRequest=$route->match($httpRequest);if($appRequest!==NULL){return$appRequest;}}return
NULL;}public
function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){if($this->cachedRoutes===NULL){$routes=array();$routes['*']=array();foreach($this
as$route){$presenter=$route
instanceof
Route?$route->getTargetPresenter():NULL;if($presenter===FALSE)continue;if(is_string($presenter)){$presenter=strtolower($presenter);if(!isset($routes[$presenter])){$routes[$presenter]=$routes['*'];}$routes[$presenter][]=$route;}else{foreach($routes
as$id=>$foo){$routes[$id][]=$route;}}}$this->cachedRoutes=$routes;}$presenter=strtolower($appRequest->getPresenterName());if(!isset($this->cachedRoutes[$presenter]))$presenter='*';foreach($this->cachedRoutes[$presenter]as$route){$uri=$route->constructUrl($appRequest,$httpRequest);if($uri!==NULL){return$uri;}}return
NULL;}}class
Route
extends
Object
implements
IRouter{const
PRESENTER_KEY='presenter';const
MODULE_KEY='module';const
CASE_SENSITIVE=256;const
FULL_META=128;const
HOST=1;const
PATH=2;const
RELATIVE=3;const
VALUE='value';const
PATTERN='pattern';const
FILTER_IN='filterIn';const
FILTER_OUT='filterOut';const
FILTER_TABLE='filterTable';const
OPTIONAL=0;const
PATH_OPTIONAL=1;const
CONSTANT=2;public
static$defaultFlags=0;public
static$styles=array('#'=>array(self::PATTERN=>'[^/]+',self::FILTER_IN=>'rawurldecode',self::FILTER_OUT=>'rawurlencode'),'?#'=>array(),'module'=>array(self::PATTERN=>'[a-z][a-z0-9.-]*',self::FILTER_IN=>array(__CLASS__,'path2presenter'),self::FILTER_OUT=>array(__CLASS__,'presenter2path')),'presenter'=>array(self::PATTERN=>'[a-z][a-z0-9.-]*',self::FILTER_IN=>array(__CLASS__,'path2presenter'),self::FILTER_OUT=>array(__CLASS__,'presenter2path')),'action'=>array(self::PATTERN=>'[a-z][a-z0-9-]*',self::FILTER_IN=>array(__CLASS__,'path2action'),self::FILTER_OUT=>array(__CLASS__,'action2path')),'?module'=>array(),'?presenter'=>array(),'?action'=>array());private$mask;private$sequence;private$re;private$metadata=array();private$xlat;private$type;private$flags;public
function
__construct($mask,array$metadata=array(),$flags=0){$this->flags=$flags|self::$defaultFlags;if(!($this->flags&self::FULL_META)){foreach($metadata
as$name=>$def){$metadata[$name]=array(self::VALUE=>$def);}}$this->setMask($mask,$metadata);}public
function
match(IHttpRequest$httpRequest){$uri=$httpRequest->getUri();if($this->type===self::HOST){$path='//'.$uri->getHost().$uri->getPath();}elseif($this->type===self::RELATIVE){$basePath=$uri->getBasePath();if(strncmp($uri->getPath(),$basePath,strlen($basePath))!==0){return
NULL;}$path=(string)substr($uri->getPath(),strlen($basePath));}else{$path=$uri->getPath();}if($path!==''){$path=rtrim($path,'/').'/';}if(!preg_match($this->re,$path,$matches)){return
NULL;}$params=array();foreach($matches
as$k=>$v){if(is_string($k)&&$v!==''){$params[str_replace('___','-',$k)]=$v;}}foreach($this->metadata
as$name=>$meta){if(isset($params[$name])){}elseif(isset($meta['fixity'])&&$meta['fixity']!==self::OPTIONAL){$params[$name]=NULL;}}if($this->xlat){$params+=self::renameKeys($httpRequest->getQuery(),array_flip($this->xlat));}else{$params+=$httpRequest->getQuery();}foreach($this->metadata
as$name=>$meta){if(isset($params[$name])){if(!is_scalar($params[$name])){}elseif(isset($meta[self::FILTER_TABLE][$params[$name]])){$params[$name]=$meta[self::FILTER_TABLE][$params[$name]];}elseif(isset($meta[self::FILTER_IN])){$params[$name]=call_user_func($meta[self::FILTER_IN],(string)$params[$name]);if($params[$name]===NULL&&!isset($meta['fixity'])){return
NULL;}}}elseif(isset($meta['fixity'])){$params[$name]=$meta[self::VALUE];}}if(!isset($params[self::PRESENTER_KEY])){throw
new
InvalidStateException('Missing presenter in route definition.');}if(isset($this->metadata[self::MODULE_KEY])){if(!isset($params[self::MODULE_KEY])){throw
new
InvalidStateException('Missing module in route definition.');}$presenter=$params[self::MODULE_KEY].':'.$params[self::PRESENTER_KEY];unset($params[self::MODULE_KEY],$params[self::PRESENTER_KEY]);}else{$presenter=$params[self::PRESENTER_KEY];unset($params[self::PRESENTER_KEY]);}return
new
PresenterRequest($presenter,$httpRequest->getMethod(),$params,$httpRequest->getPost(),$httpRequest->getFiles(),array(PresenterRequest::SECURED=>$httpRequest->isSecured()));}public
function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){if($this->flags&self::ONE_WAY){return
NULL;}$params=$appRequest->getParams();$metadata=$this->metadata;$presenter=$appRequest->getPresenterName();$params[self::PRESENTER_KEY]=$presenter;if(isset($metadata[self::MODULE_KEY])){$module=$metadata[self::MODULE_KEY];if(isset($module['fixity'])&&strncasecmp($presenter,$module[self::VALUE].':',strlen($module[self::VALUE])+1)===0){$a=strlen($module[self::VALUE]);}else{$a=strrpos($presenter,':');}if($a!==FALSE){$params[self::MODULE_KEY]=substr($presenter,0,$a);$params[self::PRESENTER_KEY]=substr($presenter,$a+1);}}foreach($metadata
as$name=>$meta){if(!isset($params[$name]))continue;if(isset($meta['fixity'])){if(is_scalar($params[$name])&&strcasecmp($params[$name],$meta[self::VALUE])===0){unset($params[$name]);continue;}elseif($meta['fixity']===self::CONSTANT){return
NULL;}}if(!is_scalar($params[$name])){}elseif(isset($meta['filterTable2'][$params[$name]])){$params[$name]=$meta['filterTable2'][$params[$name]];}elseif(isset($meta[self::FILTER_OUT])){$params[$name]=call_user_func($meta[self::FILTER_OUT],$params[$name]);}if(isset($meta[self::PATTERN])&&!preg_match($meta[self::PATTERN],$params[$name])){return
NULL;}}$sequence=$this->sequence;$brackets=array();$required=0;$uri='';$i=count($sequence)-1;do{$uri=$sequence[$i].$uri;if($i===0)break;$i--;$name=$sequence[$i];$i--;if($name===']'){$brackets[]=$uri;}elseif($name[0]==='['){$tmp=array_pop($brackets);if($required<count($brackets)+1){if($name!=='[!'){$uri=$tmp;}}else{$required=count($brackets);}}elseif($name[0]==='?'){continue;}elseif(isset($params[$name])&&$params[$name]!=''){$required=count($brackets);$uri=$params[$name].$uri;unset($params[$name]);}elseif(isset($metadata[$name]['fixity'])){$uri=$metadata[$name]['defOut'].$uri;}else{return
NULL;}}while(TRUE);if($this->xlat){$params=self::renameKeys($params,$this->xlat);}$sep=ini_get('arg_separator.input');$query=http_build_query($params,'',$sep?$sep[0]:'&');if($query!='')$uri.='?'.$query;if($this->type===self::RELATIVE){$uri='//'.$httpRequest->getUri()->getAuthority().$httpRequest->getUri()->getBasePath().$uri;}elseif($this->type===self::PATH){$uri='//'.$httpRequest->getUri()->getAuthority().$uri;}if(strpos($uri,'//',2)!==FALSE){return
NULL;}$uri=($this->flags&self::SECURED?'https:':'http:').$uri;return$uri;}private
function
setMask($mask,array$metadata){$this->mask=$mask;if(substr($mask,0,2)==='//'){$this->type=self::HOST;}elseif(substr($mask,0,1)==='/'){$this->type=self::PATH;}else{$this->type=self::RELATIVE;}foreach($metadata
as$name=>$meta){if(array_key_exists(self::VALUE,$meta)){$metadata[$name]['fixity']=self::CONSTANT;}}$parts=preg_split('/<([^># ]+) *([^>#]*)(#?[^>\[\]]*)>|(\[!?|\]|\s*\?.*)/',$mask,-1,PREG_SPLIT_DELIM_CAPTURE);$this->xlat=array();$i=count($parts)-1;if(isset($parts[$i-1])&&substr(ltrim($parts[$i-1]),0,1)==='?'){preg_match_all('/(?:([a-zA-Z0-9_.-]+)=)?<([^># ]+) *([^>#]*)(#?[^>]*)>/',$parts[$i-1],$matches,PREG_SET_ORDER);foreach($matches
as$match){list(,$param,$name,$pattern,$class)=$match;if($class!==''){if(!isset(self::$styles[$class])){throw
new
InvalidStateException("Parameter '$name' has '$class' flag, but Route::\$styles['$class'] is not set.");}$meta=self::$styles[$class];}elseif(isset(self::$styles['?'.$name])){$meta=self::$styles['?'.$name];}else{$meta=self::$styles['?#'];}if(isset($metadata[$name])){$meta=$metadata[$name]+$meta;}if(array_key_exists(self::VALUE,$meta)){$meta['fixity']=self::OPTIONAL;}unset($meta['pattern']);$meta['filterTable2']=empty($meta[self::FILTER_TABLE])?NULL:array_flip($meta[self::FILTER_TABLE]);$metadata[$name]=$meta;if($param!==''){$this->xlat[$name]=$param;}}$i-=5;}$brackets=0;$re='';$sequence=array();$autoOptional=array(0,0);do{array_unshift($sequence,$parts[$i]);if(strpos($parts[$i],'{')!==FALSE){throw
new
DeprecatedException('Optional parts delimited using {...} are deprecated; use [...] instead.');}$re=preg_quote($parts[$i],'#').$re;if($i===0)break;$i--;$part=$parts[$i];if($part==='['||$part===']'||$part==='[!'){$brackets+=$part[0]==='['?-1:1;if($brackets<0){throw
new
InvalidArgumentException("Unexpected '$part' in mask '$mask'.");}array_unshift($sequence,$part);$re=($part[0]==='['?'(?:':')?').$re;$i-=4;continue;}$class=$parts[$i];$i--;$pattern=trim($parts[$i]);$i--;$name=$parts[$i];$i--;array_unshift($sequence,$name);if($name[0]==='?'){$re='(?:'.preg_quote(substr($name,1),'#').'|'.$pattern.')'.$re;$sequence[1]=substr($name,1).$sequence[1];continue;}if(preg_match('#[^a-z0-9_-]#i',$name)){throw
new
InvalidArgumentException("Parameter name must be alphanumeric string due to limitations of PCRE, '$name' given.");}if($class!==''){if(!isset(self::$styles[$class])){throw
new
InvalidStateException("Parameter '$name' has '$class' flag, but Route::\$styles['$class'] is not set.");}$meta=self::$styles[$class];}elseif(isset(self::$styles[$name])){$meta=self::$styles[$name];}else{$meta=self::$styles['#'];}if(isset($metadata[$name])){$meta=$metadata[$name]+$meta;}if($pattern==''&&isset($meta[self::PATTERN])){$pattern=$meta[self::PATTERN];}$meta['filterTable2']=empty($meta[self::FILTER_TABLE])?NULL:array_flip($meta[self::FILTER_TABLE]);if(array_key_exists(self::VALUE,$meta)){if(isset($meta['filterTable2'][$meta[self::VALUE]])){$meta['defOut']=$meta['filterTable2'][$meta[self::VALUE]];}elseif(isset($meta[self::FILTER_OUT])){$meta['defOut']=call_user_func($meta[self::FILTER_OUT],$meta[self::VALUE]);}else{$meta['defOut']=$meta[self::VALUE];}}$meta[self::PATTERN]="#(?:$pattern)$#A".($this->flags&self::CASE_SENSITIVE?'':'i');$re='(?P<'.str_replace('-','___',$name).'>'.$pattern.')'.$re;if($brackets){if(!isset($meta[self::VALUE])){$meta[self::VALUE]=$meta['defOut']=NULL;}$meta['fixity']=self::PATH_OPTIONAL;}elseif(isset($meta['fixity'])){$re='(?:'.substr_replace($re,')?',strlen($re)-$autoOptional[0],0);array_splice($sequence,count($sequence)-$autoOptional[1],0,array(']',''));array_unshift($sequence,'[','');$meta['fixity']=self::PATH_OPTIONAL;}else{$autoOptional=array(strlen($re),count($sequence));}$metadata[$name]=$meta;}while(TRUE);if($brackets){throw
new
InvalidArgumentException("Missing closing ']' in mask '$mask'.");}$this->re='#'.$re.'/?$#A'.($this->flags&self::CASE_SENSITIVE?'':'i');$this->metadata=$metadata;$this->sequence=$sequence;}public
function
getMask(){return$this->mask;}public
function
getDefaults(){$defaults=array();foreach($this->metadata
as$name=>$meta){if(isset($meta['fixity'])){$defaults[$name]=$meta[self::VALUE];}}return$defaults;}public
function
getTargetPresenter(){if($this->flags&self::ONE_WAY){return
FALSE;}$m=$this->metadata;$module='';if(isset($m[self::MODULE_KEY])){if(isset($m[self::MODULE_KEY]['fixity'])&&$m[self::MODULE_KEY]['fixity']===self::CONSTANT){$module=$m[self::MODULE_KEY][self::VALUE].':';}else{return
NULL;}}if(isset($m[self::PRESENTER_KEY]['fixity'])&&$m[self::PRESENTER_KEY]['fixity']===self::CONSTANT){return$module.$m[self::PRESENTER_KEY][self::VALUE];}return
NULL;}private
static
function
renameKeys($arr,$xlat){if(empty($xlat))return$arr;$res=array();$occupied=array_flip($xlat);foreach($arr
as$k=>$v){if(isset($xlat[$k])){$res[$xlat[$k]]=$v;}elseif(!isset($occupied[$k])){$res[$k]=$v;}}return$res;}private
static
function
action2path($s){$s=preg_replace('#(.)(?=[A-Z])#','$1-',$s);$s=strtolower($s);$s=rawurlencode($s);return$s;}private
static
function
path2action($s){$s=strtolower($s);$s=preg_replace('#-(?=[a-z])#',' ',$s);$s=substr(ucwords('x'.$s),1);$s=str_replace(' ','',$s);return$s;}private
static
function
presenter2path($s){$s=strtr($s,':','.');$s=preg_replace('#([^.])(?=[A-Z])#','$1-',$s);$s=strtolower($s);$s=rawurlencode($s);return$s;}private
static
function
path2presenter($s){$s=strtolower($s);$s=preg_replace('#([.-])(?=[a-z])#','$1 ',$s);$s=ucwords($s);$s=str_replace('. ',':',$s);$s=str_replace('- ','',$s);return$s;}public
static
function
addStyle($style,$parent='#'){if(isset(self::$styles[$style])){throw
new
InvalidArgumentException("Style '$style' already exists.");}if($parent!==NULL){if(!isset(self::$styles[$parent])){throw
new
InvalidArgumentException("Parent style '$parent' doesn't exist.");}self::$styles[$style]=self::$styles[$parent];}else{self::$styles[$style]=array();}}public
static
function
setStyleProperty($style,$key,$value){if(!isset(self::$styles[$style])){throw
new
InvalidArgumentException("Style '$style' doesn't exist.");}self::$styles[$style][$key]=$value;}}class
SimpleRouter
extends
Object
implements
IRouter{const
PRESENTER_KEY='presenter';const
MODULE_KEY='module';private$module='';private$defaults;private$flags;public
function
__construct($defaults=array(),$flags=0){if(is_string($defaults)){$a=strrpos($defaults,':');$defaults=array(self::PRESENTER_KEY=>substr($defaults,0,$a),'action'=>substr($defaults,$a+1));}if(isset($defaults[self::MODULE_KEY])){$this->module=$defaults[self::MODULE_KEY].':';unset($defaults[self::MODULE_KEY]);}$this->defaults=$defaults;$this->flags=$flags;}public
function
match(IHttpRequest$httpRequest){$params=$httpRequest->getQuery();$params+=$this->defaults;if(!isset($params[self::PRESENTER_KEY])){throw
new
InvalidStateException('Missing presenter.');}$presenter=$this->module.$params[self::PRESENTER_KEY];unset($params[self::PRESENTER_KEY]);return
new
PresenterRequest($presenter,$httpRequest->getMethod(),$params,$httpRequest->getPost(),$httpRequest->getFiles(),array(PresenterRequest::SECURED=>$httpRequest->isSecured()));}public
function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){$params=$appRequest->getParams();$presenter=$appRequest->getPresenterName();if(strncasecmp($presenter,$this->module,strlen($this->module))===0){$params[self::PRESENTER_KEY]=substr($presenter,strlen($this->module));}else{return
NULL;}foreach($this->defaults
as$key=>$value){if(isset($params[$key])&&$params[$key]==$value){unset($params[$key]);}}$uri=$httpRequest->getUri();$uri=($this->flags&self::SECURED?'https://':'http://').$uri->getAuthority().$uri->getScriptPath();$sep=ini_get('arg_separator.input');$query=http_build_query($params,'',$sep?$sep[0]:'&');if($query!=''){$uri.='?'.$query;}return$uri;}public
function
getDefaults(){return$this->defaults;}}class
Cache
extends
Object
implements
ArrayAccess{const
PRIORITY='priority';const
EXPIRE='expire';const
SLIDING='sliding';const
TAGS='tags';const
FILES='files';const
ITEMS='items';const
CONSTS='consts';const
CALLBACKS='callbacks';const
ALL='all';const
REFRESH='sliding';const
NAMESPACE_SEPARATOR="\x00";private$storage;private$namespace;private$key;private$data;public
function
__construct(ICacheStorage$storage,$namespace=NULL){$this->storage=$storage;$this->namespace=(string)$namespace;if(strpos($this->namespace,self::NAMESPACE_SEPARATOR)!==FALSE){throw
new
InvalidArgumentException("Namespace name contains forbidden character.");}}public
function
getStorage(){return$this->storage;}public
function
getNamespace(){return$this->namespace;}public
function
release(){$this->key=$this->data=NULL;}public
function
save($key,$data,array$dp=NULL){if(!is_string($key)){throw
new
InvalidArgumentException("Cache key name must be string, ".gettype($key)." given.");}if(!empty($dp[Cache::EXPIRE])){$expire=&$dp[Cache::EXPIRE];if(is_string($expire)&&!is_numeric($expire)){$expire=strtotime($expire)-time();}elseif($expire>Tools::YEAR){$expire-=time();}}if(isset($dp[self::FILES])){foreach((array)$dp[self::FILES]as$item){$dp[self::CALLBACKS][]=array(array(__CLASS__,'checkFile'),$item,@filemtime($item));}unset($dp[self::FILES]);}if(isset($dp[self::ITEMS])){$dp[self::ITEMS]=(array)$dp[self::ITEMS];foreach($dp[self::ITEMS]as$k=>$item){$dp[self::ITEMS][$k]=$this->namespace.self::NAMESPACE_SEPARATOR.$item;}}if(isset($dp[self::CONSTS])){foreach((array)$dp[self::CONSTS]as$item){$dp[self::CALLBACKS][]=array(array(__CLASS__,'checkConst'),$item,constant($item));}unset($dp[self::CONSTS]);}$this->key=NULL;$this->storage->write($this->namespace.self::NAMESPACE_SEPARATOR.$key,$data,(array)$dp);}public
function
clean(array$conds=NULL){$this->storage->clean((array)$conds);}public
function
offsetSet($key,$data){if(!is_string($key)){throw
new
InvalidArgumentException("Cache key name must be string, ".gettype($key)." given.");}$this->key=$this->data=NULL;if($data===NULL){$this->storage->remove($this->namespace.self::NAMESPACE_SEPARATOR.$key);}else{$this->storage->write($this->namespace.self::NAMESPACE_SEPARATOR.$key,$data,array());}}public
function
offsetGet($key){if(!is_string($key)){throw
new
InvalidArgumentException("Cache key name must be string, ".gettype($key)." given.");}if($this->key===$key){return$this->data;}$this->key=$key;$this->data=$this->storage->read($this->namespace.self::NAMESPACE_SEPARATOR.$key);return$this->data;}public
function
offsetExists($key){if(!is_string($key)){throw
new
InvalidArgumentException("Cache key name must be string, ".gettype($key)." given.");}$this->key=$key;$this->data=$this->storage->read($this->namespace.self::NAMESPACE_SEPARATOR.$key);return$this->data!==NULL;}public
function
offsetUnset($key){if(!is_string($key)){throw
new
InvalidArgumentException("Cache key name must be string, ".gettype($key)." given.");}$this->key=$this->data=NULL;$this->storage->remove($this->namespace.self::NAMESPACE_SEPARATOR.$key);}public
static
function
checkCallbacks($callbacks){foreach($callbacks
as$callback){$func=array_shift($callback);if(!call_user_func_array($func,$callback)){return
FALSE;}}return
TRUE;}private
static
function
checkConst($const,$value){return
defined($const)&&constant($const)===$value;}private
static
function
checkFile($file,$time){return@filemtime($file)==$time;}}interface
ICacheStorage{function
read($key);function
write($key,$data,array$dependencies);function
remove($key);function
clean(array$conds);}class
DummyStorage
extends
Object
implements
ICacheStorage{public
function
read($key){return
NULL;}public
function
write($key,$data,array$dp){return
TRUE;}public
function
remove($key){return
TRUE;}public
function
clean(array$conds){return
TRUE;}}class
FileStorage
extends
Object
implements
ICacheStorage{const
META_HEADER_LEN=28;const
META_TIME='time';const
META_SERIALIZED='serialized';const
META_PRIORITY='priority';const
META_EXPIRE='expire';const
META_DELTA='delta';const
META_ITEMS='di';const
META_TAGS='tags';const
META_CALLBACKS='callbacks';const
FILE='file';const
HANDLE='handle';public
static$gcProbability=0.001;public
static$useDirectories;private$dir;private$useDirs;public
function
__construct($dir){if(self::$useDirectories===NULL){self::$useDirectories=!ini_get('safe_mode');$uniq=uniqid();umask(0000);if(!@mkdir("$dir/$uniq",0777)){throw
new
InvalidStateException("Unable to write to directory '$dir'. Make this directory writable.");}if(!self::$useDirectories&&@file_put_contents("$dir/$uniq/_",'')!==FALSE){self::$useDirectories=TRUE;unlink("$dir/$uniq/_");}rmdir("$dir/$uniq");}$this->dir=$dir;$this->useDirs=(bool)self::$useDirectories;if(mt_rand()/mt_getrandmax()<self::$gcProbability){$this->clean(array());}}public
function
read($key){$meta=$this->readMeta($this->getCacheFile($key),LOCK_SH);if($meta&&$this->verify($meta)){return$this->readData($meta);}else{return
NULL;}}private
function
verify($meta){do{if(!empty($meta[self::META_DELTA])){if(filemtime($meta[self::FILE])+$meta[self::META_DELTA]<time())break;touch($meta[self::FILE]);}elseif(!empty($meta[self::META_EXPIRE])&&$meta[self::META_EXPIRE]<time()){break;}if(!empty($meta[self::META_CALLBACKS])&&!Cache::checkCallbacks($meta[self::META_CALLBACKS])){break;}if(!empty($meta[self::META_ITEMS])){foreach($meta[self::META_ITEMS]as$depFile=>$time){$m=$this->readMeta($depFile,LOCK_SH);if($m[self::META_TIME]!==$time)break
2;if($m&&!$this->verify($m))break
2;}}return
TRUE;}while(FALSE);$this->delete($meta[self::HANDLE],$meta[self::FILE]);return
FALSE;}public
function
write($key,$data,array$dp){$meta=array(self::META_TIME=>microtime());if(!is_string($data)){$data=serialize($data);$meta[self::META_SERIALIZED]=TRUE;}if(isset($dp[Cache::PRIORITY])){$meta[self::META_PRIORITY]=(int)$dp[Cache::PRIORITY];}if(!empty($dp[Cache::EXPIRE])){if(empty($dp[Cache::SLIDING])){$meta[self::META_EXPIRE]=$dp[Cache::EXPIRE]+time();}else{$meta[self::META_DELTA]=(int)$dp[Cache::EXPIRE];}}if(!empty($dp[Cache::TAGS])){$meta[self::META_TAGS]=array_flip(array_values((array)$dp[Cache::TAGS]));}if(!empty($dp[Cache::ITEMS])){foreach((array)$dp[Cache::ITEMS]as$item){$depFile=$this->getCacheFile($item);$m=$this->readMeta($depFile,LOCK_SH);$meta[self::META_ITEMS][$depFile]=$m[self::META_TIME];unset($m);}}if(!empty($dp[Cache::CALLBACKS])){$meta[self::META_CALLBACKS]=$dp[Cache::CALLBACKS];}$cacheFile=$this->getCacheFile($key);if($this->useDirs&&!is_dir($dir=dirname($cacheFile))){umask(0000);if(!mkdir($dir,0777,TRUE)){return
FALSE;}}$handle=@fopen($cacheFile,'r+b');if(!$handle){$handle=fopen($cacheFile,'wb');if(!$handle){return
FALSE;}}flock($handle,LOCK_EX);ftruncate($handle,0);$head=serialize($meta).'?>';$head='<?php //netteCache[01]'.str_pad((string)strlen($head),6,'0',STR_PAD_LEFT).$head;$headLen=strlen($head);$dataLen=strlen($data);if(fwrite($handle,str_repeat("\x00",$headLen),$headLen)===$headLen){if(fwrite($handle,$data,$dataLen)===$dataLen){fseek($handle,0);if(fwrite($handle,$head,$headLen)===$headLen){fclose($handle);return
TRUE;}}}$this->delete($handle,$cacheFile);return
TRUE;}public
function
remove($key){$cacheFile=$this->getCacheFile($key);$meta=$this->readMeta($cacheFile,LOCK_EX);if($meta){$this->delete($meta[self::HANDLE],$cacheFile);}return
TRUE;}public
function
clean(array$conds){$tags=isset($conds[Cache::TAGS])?array_flip((array)$conds[Cache::TAGS]):array();$priority=isset($conds[Cache::PRIORITY])?$conds[Cache::PRIORITY]:-1;$all=!empty($conds[Cache::ALL]);$now=time();$base=$this->dir.DIRECTORY_SEPARATOR.'c';$iterator=new
RecursiveIteratorIterator(new
RecursiveDirectoryIterator($this->dir),RecursiveIteratorIterator::CHILD_FIRST);foreach($iterator
as$entry){if(strncmp($entry,$base,strlen($base))){continue;}if($entry->isDir()){@rmdir((string)$entry);continue;}do{$meta=$this->readMeta((string)$entry,LOCK_SH);if(!$meta)continue
2;if($all)break;if(!empty($meta[self::META_EXPIRE])&&$meta[self::META_EXPIRE]<$now){break;}if(!empty($meta[self::META_PRIORITY])&&$meta[self::META_PRIORITY]<=$priority){break;}if(!empty($meta[self::META_TAGS])&&array_intersect_key($tags,$meta[self::META_TAGS])){break;}fclose($meta[self::HANDLE]);continue
2;}while(FALSE);$this->delete($meta[self::HANDLE],(string)$entry);}return
TRUE;}protected
function
readMeta($file,$lock){$handle=@fopen($file,'r+b');if(!$handle)return
NULL;flock($handle,$lock);$head=stream_get_contents($handle,self::META_HEADER_LEN);if($head&&strlen($head)===self::META_HEADER_LEN){$size=(int)substr($head,-6);$meta=stream_get_contents($handle,$size,self::META_HEADER_LEN);$meta=@unserialize($meta);if(is_array($meta)){fseek($handle,$size+self::META_HEADER_LEN);$meta[self::FILE]=$file;$meta[self::HANDLE]=$handle;return$meta;}}fclose($handle);return
NULL;}protected
function
readData($meta){$data=stream_get_contents($meta[self::HANDLE]);fclose($meta[self::HANDLE]);if(empty($meta[self::META_SERIALIZED])){return$data;}else{return@unserialize($data);}}protected
function
getCacheFile($key){if($this->useDirs){$key=explode(Cache::NAMESPACE_SEPARATOR,$key,2);return$this->dir.'/c'.(isset($key[1])?'-'.urlencode($key[0]).'/_'.urlencode($key[1]):'_'.urlencode($key[0]));}else{return$this->dir.'/c_'.urlencode($key);}}private
static
function
delete($handle,$file){if(@unlink($file)){fclose($handle);}else{flock($handle,LOCK_EX);ftruncate($handle,0);fclose($handle);@unlink($file);}}}class
MemcachedStorage
extends
Object
implements
ICacheStorage{const
META_CALLBACKS='callbacks';const
META_DATA='data';const
META_DELTA='delta';private$memcache;private$prefix;public
static
function
isAvailable(){return
extension_loaded('memcache');}public
function
__construct($host='localhost',$port=11211,$prefix=''){if(!self::isAvailable()){throw
new
Exception("PHP extension 'memcache' is not loaded.");}$this->prefix=$prefix;$this->memcache=new
Memcache;$this->memcache->connect($host,$port);}public
function
read($key){$key=$this->prefix.$key;$meta=$this->memcache->get($key);if(!$meta)return
NULL;if(!empty($meta[self::META_CALLBACKS])&&!Cache::checkCallbacks($meta[self::META_CALLBACKS])){$this->memcache->delete($key);return
NULL;}if(!empty($meta[self::META_DELTA])){$this->memcache->replace($key,$meta,0,$meta[self::META_DELTA]+time());}return$meta[self::META_DATA];}public
function
write($key,$data,array$dp){if(!empty($dp[Cache::TAGS])||isset($dp[Cache::PRIORITY])||!empty($dp[Cache::ITEMS])){throw
new
NotSupportedException('Tags, priority and dependent items are not supported by MemcachedStorage.');}$meta=array(self::META_DATA=>$data);$expire=0;if(!empty($dp[Cache::EXPIRE])){$expire=(int)$dp[Cache::EXPIRE];if(!empty($dp[Cache::SLIDING])){$meta[self::META_DELTA]=$expire;}}if(!empty($dp[Cache::CALLBACKS])){$meta[self::META_CALLBACKS]=$dp[Cache::CALLBACKS];}return$this->memcache->set($this->prefix.$key,$meta,0,$expire);}public
function
remove($key){return$this->memcache->delete($this->prefix.$key);}public
function
clean(array$conds){if(!empty($conds[Cache::ALL])){$this->memcache->flush();}elseif(isset($conds[Cache::TAGS])||isset($conds[Cache::PRIORITY])){throw
new
NotSupportedException('Tags and priority is not supported by MemcachedStorage.');}return
TRUE;}}interface
IMap
extends
ICollection,ArrayAccess{function
add($key,$value);function
search($item);function
getKeys();}class
KeyNotFoundException
extends
RuntimeException{}class
Hashtable
extends
Collection
implements
IMap{private$throwKeyNotFound=FALSE;public
function
add($key,$item){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer, ".gettype($key)." given.");}if(parent::offsetExists($key)){throw
new
InvalidStateException('An element with the same key already exists.');}$this->beforeAdd($item);parent::offsetSet($key,$item);return
TRUE;}public
function
append($item){throw
new
NotSupportedException;}public
function
getKeys(){return
array_keys($this->getArrayCopy());}public
function
search($item){return
array_search($item,$this->getArrayCopy(),TRUE);}public
function
import($arr){$this->updating();if(!(is_array($arr)||$arr
instanceof
Traversable)){throw
new
InvalidArgumentException("Argument must be traversable.");}if($this->getItemType()===NULL){$this->setArray((array)$arr);}else{$this->clear();foreach($arr
as$key=>$item){$this->offsetSet($key,$item);}}}public
function
get($key,$default=NULL){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer, ".gettype($key)." given.");}if(parent::offsetExists($key)){return
parent::offsetGet($key);}else{return$default;}}public
function
throwKeyNotFound($val=TRUE){$this->throwKeyNotFound=(bool)$val;}public
function
offsetSet($key,$item){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer, ".gettype($key)." given.");}$this->beforeAdd($item);parent::offsetSet($key,$item);}public
function
offsetGet($key){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer, ".gettype($key)." given.");}if(parent::offsetExists($key)){return
parent::offsetGet($key);}elseif($this->throwKeyNotFound){throw
new
KeyNotFoundException;}else{return
NULL;}}public
function
offsetExists($key){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer, ".gettype($key)." given.");}return
parent::offsetExists($key);}public
function
offsetUnset($key){$this->updating();if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer, ".gettype($key)." given.");}if(parent::offsetExists($key)){parent::offsetUnset($key);}}}interface
ISet
extends
ICollection{}class
Set
extends
Collection
implements
ISet{public
function
append($item){$this->beforeAdd($item);if(is_object($item)){$key=spl_object_hash($item);if(parent::offsetExists($key)){return
FALSE;}parent::offsetSet($key,$item);return
TRUE;}else{$key=$this->search($item);if($key===FALSE){parent::offsetSet(NULL,$item);return
TRUE;}return
FALSE;}}protected
function
search($item){if(is_object($item)){$key=spl_object_hash($item);return
parent::offsetExists($key)?$key:FALSE;}else{return
array_search($item,$this->getArrayCopy(),TRUE);}}public
function
offsetSet($key,$item){if($key===NULL){$this->append($item);}else{throw
new
NotSupportedException;}}public
function
offsetGet($key){throw
new
NotSupportedException;}public
function
offsetExists($key){throw
new
NotSupportedException;}public
function
offsetUnset($key){throw
new
NotSupportedException;}}class
Config
extends
Hashtable{const
READONLY=1;const
EXPAND=2;private
static$extensions=array('ini'=>'ConfigAdapterIni','xml'=>'ConfigAdapterXml');public
static
function
registerExtension($extension,$class){if(!class_exists($class)){throw
new
InvalidArgumentException("Class '$class' was not found.");}$reflection=new
ReflectionClass($class);if(!$reflection->implementsInterface('IConfigAdapter')){throw
new
InvalidArgumentException("Configuration adapter '$class' is not Nette\\Config\\IConfigAdapter implementor.");}self::$extensions[strtolower($extension)]=$class;}public
static
function
fromFile($file,$section=NULL,$flags=self::READONLY){$extension=strtolower(pathinfo($file,PATHINFO_EXTENSION));if(isset(self::$extensions[$extension])){$arr=call_user_func(array(self::$extensions[$extension],'load'),$file,$section);return
new
self($arr,$flags);}else{throw
new
InvalidArgumentException("Unknown file extension '$file'.");}}public
function
__construct($arr=NULL,$flags=self::READONLY){parent::__construct($arr);if($arr!==NULL){if($flags&self::EXPAND){$this->expand();}if($flags&self::READONLY){$this->freeze();}}}public
function
save($file,$section=NULL){$extension=strtolower(pathinfo($file,PATHINFO_EXTENSION));if(isset(self::$extensions[$extension])){return
call_user_func(array(self::$extensions[$extension],'save'),$this,$file,$section);}else{throw
new
InvalidArgumentException("Unknown file extension '$file'.");}}public
function
expand(){$this->updating();$data=$this->getArrayCopy();foreach($data
as$key=>$val){if(is_string($val)){$data[$key]=Environment::expand($val);}elseif($val
instanceof
self){$val->expand();}}$this->setArray($data);}public
function
import($arr){$this->updating();foreach($arr
as$key=>$val){if(is_array($val)){$arr[$key]=$obj=clone$this;$obj->import($val);}}$this->setArray($arr);}public
function
toArray(){$res=$this->getArrayCopy();foreach($res
as$key=>$val){if($val
instanceof
self){$res[$key]=$val->toArray();}}return$res;}public
function
freeze(){parent::freeze();foreach($this->getArrayCopy()as$val){if($val
instanceof
self){$val->freeze();}}}public
function
__clone(){parent::__clone();$data=$this->getArrayCopy();foreach($data
as$key=>$val){if($val
instanceof
self){$data[$key]=clone$val;}}$this->setArray($data);}public
function&__get($key){$val=$this->offsetGet($key);return$val;}public
function
__set($key,$item){$this->offsetSet($key,$item);}public
function
__isset($key){return$this->offsetExists($key);}public
function
__unset($key){$this->offsetUnset($key);}}interface
IConfigAdapter{static
function
load($file,$section=NULL);static
function
save($config,$file,$section=NULL);}final
class
ConfigAdapterIni
implements
IConfigAdapter{public
static$keySeparator='.';public
static$sectionSeparator=' < ';public
static$rawSection='!';final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
load($file,$section=NULL){if(!is_file($file)||!is_readable($file)){throw
new
FileNotFoundException("File '$file' is missing or is not readable.");}Tools::tryError();$ini=parse_ini_file($file,TRUE);if(Tools::catchError($msg)){throw
new
Exception($msg);}$separator=trim(self::$sectionSeparator);$data=array();foreach($ini
as$secName=>$secData){if(is_array($secData)){if(substr($secName,-1)===self::$rawSection){$secName=substr($secName,0,-1);}elseif(self::$keySeparator){$tmp=array();foreach($secData
as$key=>$val){$cursor=&$tmp;foreach(explode(self::$keySeparator,$key)as$part){if(!isset($cursor[$part])||is_array($cursor[$part])){$cursor=&$cursor[$part];}else{throw
new
InvalidStateException("Invalid key '$key' in section [$secName] in '$file'.");}}$cursor=$val;}$secData=$tmp;}$parts=$separator?explode($separator,strtr($secName,':',$separator)):array($secName);if(count($parts)>1){$parent=trim($parts[1]);$cursor=&$data;foreach(self::$keySeparator?explode(self::$keySeparator,$parent):array($parent)as$part){if(isset($cursor[$part])&&is_array($cursor[$part])){$cursor=&$cursor[$part];}else{throw
new
InvalidStateException("Missing parent section [$parent] in '$file'.");}}$secData=ArrayTools::mergeTree($secData,$cursor);}$secName=trim($parts[0]);if($secName===''){throw
new
InvalidStateException("Invalid empty section name in '$file'.");}}if(self::$keySeparator){$cursor=&$data;foreach(explode(self::$keySeparator,$secName)as$part){if(!isset($cursor[$part])||is_array($cursor[$part])){$cursor=&$cursor[$part];}else{throw
new
InvalidStateException("Invalid section [$secName] in '$file'.");}}}else{$cursor=&$data[$secName];}if(is_array($secData)&&is_array($cursor)){$secData=ArrayTools::mergeTree($secData,$cursor);}$cursor=$secData;}if($section===NULL){return$data;}elseif(!isset($data[$section])||!is_array($data[$section])){throw
new
InvalidStateException("There is not section [$section] in '$file'.");}else{return$data[$section];}}public
static
function
save($config,$file,$section=NULL){$output=array();$output[]='; generated by Nette';$output[]='';if($section===NULL){foreach($config
as$secName=>$secData){if(!(is_array($secData)||$secData
instanceof
Traversable)){throw
new
InvalidStateException("Invalid section '$section'.");}$output[]="[$secName]";self::build($secData,$output,'');$output[]='';}}else{$output[]="[$section]";self::build($config,$output,'');$output[]='';}if(!file_put_contents($file,implode(PHP_EOL,$output))){throw
new
IOException("Cannot write file '$file'.");}}private
static
function
build($input,&$output,$prefix){foreach($input
as$key=>$val){if(is_array($val)||$val
instanceof
Traversable){self::build($val,$output,$prefix.$key.self::$keySeparator);}elseif(is_bool($val)){$output[]="$prefix$key = ".($val?'true':'false');}elseif(is_numeric($val)){$output[]="$prefix$key = $val";}elseif(is_string($val)){$output[]="$prefix$key = \"$val\"";}else{throw
new
InvalidArgumentException("The '$prefix$key' item must be scalar or array, ".gettype($val)." given.");}}}}final
class
ConfigAdapterXml
implements
IConfigAdapter{final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
load($file,$section=NULL){throw
new
NotImplementedException;if(!is_file($file)||!is_readable($file)){throw
new
FileNotFoundException("File '$file' is missing or is not readable.");}$data=new
SimpleXMLElement($file,NULL,TRUE);foreach($data
as$secName=>$secData){if($secData['extends']){}}return$data;}public
static
function
save($config,$file,$section=NULL){throw
new
NotImplementedException;}}class
FormGroup
extends
Object{protected$controls;private$options=array();public
function
__construct(){$this->controls=new
SplObjectStorage;}public
function
add(){foreach(func_get_args()as$num=>$item){if($item
instanceof
IFormControl){$this->controls->attach($item);}elseif($item
instanceof
Traversable||is_array($item)){foreach($item
as$control){$this->controls->attach($control);}}else{throw
new
InvalidArgumentException("Only IFormControl items are allowed, the #$num parameter is invalid.");}}return$this;}public
function
getControls(){return
iterator_to_array($this->controls);}public
function
setOption($key,$value){if($value===NULL){unset($this->options[$key]);}else{$this->options[$key]=$value;}return$this;}final
public
function
getOption($key,$default=NULL){return
isset($this->options[$key])?$this->options[$key]:$default;}final
public
function
getOptions(){return$this->options;}}interface
IFormControl{function
loadHttpData();function
setValue($value);function
getValue();function
getRules();function
getErrors();function
isDisabled();function
translate($s);}interface
IFormRenderer{function
render(Form$form);}interface
ISubmitterControl
extends
IFormControl{function
isSubmittedBy();function
getValidationScope();}final
class
Rule
extends
Object{const
CONDITION=1;const
VALIDATOR=2;const
FILTER=3;const
TERMINATOR=4;public$control;public$operation;public$arg;public$type;public$isNegative=FALSE;public$message;public$breakOnFailure=TRUE;public$subRules;}final
class
Rules
extends
Object
implements
IteratorAggregate{const
VALIDATE_PREFIX='validate';public
static$defaultMessages=array();private$rules=array();private$parent;private$toggles=array();private$control;public
function
__construct(IFormControl$control){$this->control=$control;}public
function
addRule($operation,$message=NULL,$arg=NULL){$rule=new
Rule;$rule->control=$this->control;$rule->operation=$operation;$this->adjustOperation($rule);$rule->arg=$arg;$rule->type=Rule::VALIDATOR;if($message===NULL&&isset(self::$defaultMessages[$rule->operation])){$rule->message=self::$defaultMessages[$rule->operation];}else{$rule->message=$message;}if($this->parent===NULL){$this->control->notifyRule($rule);}$this->rules[]=$rule;return$this;}public
function
addCondition($operation,$arg=NULL){return$this->addConditionOn($this->control,$operation,$arg);}public
function
addConditionOn(IFormControl$control,$operation,$arg=NULL){$rule=new
Rule;$rule->control=$control;$rule->operation=$operation;$this->adjustOperation($rule);$rule->arg=$arg;$rule->type=Rule::CONDITION;$rule->subRules=new
self($this->control);$rule->subRules->parent=$this;$this->rules[]=$rule;return$rule->subRules;}public
function
elseCondition(){$rule=clone
end($this->parent->rules);$rule->isNegative=!$rule->isNegative;$rule->subRules=new
self($this->parent->control);$rule->subRules->parent=$this->parent;$this->parent->rules[]=$rule;return$rule->subRules;}public
function
endCondition(){return$this->parent;}public
function
toggle($id,$hide=TRUE){$this->toggles[$id]=$hide;return$this;}public
function
validate($onlyCheck=FALSE){$valid=TRUE;foreach($this->rules
as$rule){if($rule->control->isDisabled())continue;$success=($rule->isNegative
xor
call_user_func($this->getCallback($rule),$rule->control,$rule->arg));if($rule->type===Rule::CONDITION&&$success){$success=$rule->subRules->validate($onlyCheck);$valid=$valid&&$success;}elseif($rule->type===Rule::VALIDATOR&&!$success){if($onlyCheck){return
FALSE;}$rule->control->addError(vsprintf($rule->control->translate($rule->message),(array)$rule->arg));$valid=FALSE;if($rule->breakOnFailure){break;}}}return$valid;}final
public
function
getIterator(){return
new
ArrayIterator($this->rules);}final
public
function
getToggles(){return$this->toggles;}private
function
adjustOperation($rule){if(is_string($rule->operation)&&ord($rule->operation[0])>127){$rule->isNegative=TRUE;$rule->operation=~$rule->operation;}if(!is_callable($this->getCallback($rule))){$operation=is_scalar($rule->operation)?" '$rule->operation'":'';throw
new
InvalidArgumentException("Unknown operation$operation for control '{$rule->control->name}'.");}}private
function
getCallback($rule){$op=$rule->operation;if(is_string($op)&&strncmp($op,':',1)===0){return
array($rule->control->getClass(),self::VALIDATE_PREFIX.ltrim($op,':'));}else{fixCallback($op);return$op;}}}abstract
class
FormControl
extends
Component
implements
IFormControl{public
static$idMask='frm%s-%s';public$caption;protected$value;protected$control;protected$label;private$errors=array();private$disabled=FALSE;private$htmlId;private$htmlName;private$rules;private$translator=TRUE;private$options=array();public
function
__construct($caption=NULL){$this->monitor('Nette\Forms\Form');parent::__construct();$this->control=Html::el('input');$this->label=Html::el('label');$this->caption=$caption;$this->rules=new
Rules($this);}protected
function
attached($form){if(!$this->disabled&&$form
instanceof
Form&&$form->isAnchored()&&$form->isSubmitted()){$this->htmlName=NULL;$this->loadHttpData();}}public
function
setParent(IComponentContainer$parent=NULL,$name=NULL){if($name==='submit'){throw
new
InvalidArgumentException("Name 'submit' is not allowed due to JavaScript limitations.");}return
parent::setParent($parent,$name);}public
function
getForm($need=TRUE){return$this->lookup('Nette\Forms\Form',$need);}public
function
getHtmlName(){if($this->htmlName===NULL){$s='';$name=$this->getName();$obj=$this->lookup('Nette\Forms\INamingContainer',TRUE);while(!($obj
instanceof
Form)){$s="[$name]$s";$name=$obj->getName();$obj=$obj->lookup('Nette\Forms\INamingContainer',TRUE);}$this->htmlName="$name$s";}return$this->htmlName;}public
function
setHtmlId($id){$this->htmlId=$id;return$this;}public
function
getHtmlId(){if($this->htmlId===FALSE){return
NULL;}elseif($this->htmlId===NULL){$this->htmlId=sprintf(self::$idMask,$this->getForm()->getName(),$this->getHtmlName());$this->htmlId=str_replace(array('[',']'),array('-',''),$this->htmlId);}return$this->htmlId;}public
function
setOption($key,$value){if($value===NULL){unset($this->options[$key]);}else{$this->options[$key]=$value;}return$this;}final
public
function
getOption($key,$default=NULL){return
isset($this->options[$key])?$this->options[$key]:$default;}final
public
function
getOptions(){return$this->options;}public
function
setTranslator(ITranslator$translator=NULL){$this->translator=$translator;return$this;}final
public
function
getTranslator(){if($this->translator===TRUE){return$this->getForm(FALSE)?$this->getForm()->getTranslator():NULL;}return$this->translator;}public
function
translate($s){$translator=$this->getTranslator();return$translator===NULL?$s:$translator->translate($s);}public
function
setValue($value){$this->value=$value;return$this;}public
function
getValue(){return$this->value;}public
function
setDefaultValue($value){$form=$this->getForm(FALSE);if(!$form||!$form->isAnchored()||!$form->isSubmitted()){$this->setValue($value);}return$this;}public
function
loadHttpData(){$path=explode('[',strtr(str_replace(']','',$this->getHtmlName()),'.','_'));$this->setValue(ArrayTools::get($this->getForm()->getHttpData(),$path));}public
function
setDisabled($value=TRUE){$this->disabled=(bool)$value;return$this;}public
function
isDisabled(){return$this->disabled;}public
function
getControl(){$this->setOption('rendered',TRUE);$control=clone$this->control;$control->name=$this->getHtmlName();$control->disabled=$this->disabled;$control->id=$this->getHtmlId();return$control;}public
function
getLabel($caption=NULL){$label=clone$this->label;$label->for=$this->getHtmlId();if($caption!==NULL){$label->setText($this->translate($caption));}elseif($this->caption
instanceof
Html){$label->add($this->caption);}else{$label->setText($this->translate($this->caption));}return$label;}final
public
function
getControlPrototype(){return$this->control;}final
public
function
getLabelPrototype(){return$this->label;}public
function
setRendered($value=TRUE){$this->setOption('rendered',$value);return$this;}public
function
isRendered(){return!empty($this->options['rendered']);}public
function
addRule($operation,$message=NULL,$arg=NULL){$this->rules->addRule($operation,$message,$arg);return$this;}public
function
addCondition($operation,$value=NULL){return$this->rules->addCondition($operation,$value);}public
function
addConditionOn(IFormControl$control,$operation,$value=NULL){return$this->rules->addConditionOn($control,$operation,$value);}final
public
function
getRules(){return$this->rules;}final
public
function
setRequired($message=NULL){$this->rules->addRule(':Filled',$message);return$this;}final
public
function
isRequired(){return!empty($this->options['required']);}public
function
notifyRule(Rule$rule){if(is_string($rule->operation)&&strcasecmp($rule->operation,':filled')===0){$this->setOption('required',TRUE);}}public
static
function
validateEqual(IFormControl$control,$arg){$value=(string)$control->getValue();foreach((is_array($arg)?$arg:array($arg))as$item){if($item
instanceof
IFormControl){if($value===(string)$item->value)return
TRUE;}else{if($value===(string)$item)return
TRUE;}}return
FALSE;}public
static
function
validateFilled(IFormControl$control){return(string)$control->getValue()!=='';}public
static
function
validateValid(IFormControl$control){return$control->rules->validate(TRUE);}public
function
addError($message){if(!in_array($message,$this->errors,TRUE)){$this->errors[]=$message;}$this->getForm()->addError($message);}public
function
getErrors(){return$this->errors;}public
function
hasErrors(){return(bool)$this->errors;}public
function
cleanErrors(){$this->errors=array();}}class
Button
extends
FormControl{public
function
__construct($caption=NULL){parent::__construct($caption);$this->control->type='button';}public
function
getLabel($caption=NULL){return
NULL;}public
function
getControl($caption=NULL){$control=parent::getControl();$control->value=$this->translate($caption===NULL?$this->caption:$caption);return$control;}}class
Checkbox
extends
FormControl{public
function
__construct($label=NULL){parent::__construct($label);$this->control->type='checkbox';$this->value=FALSE;}public
function
setValue($value){$this->value=is_scalar($value)?(bool)$value:FALSE;return$this;}public
function
getControl(){return
parent::getControl()->checked($this->value);}}class
FileUpload
extends
FormControl{public
function
__construct($label=NULL){parent::__construct($label);$this->control->type='file';}protected
function
attached($form){if($form
instanceof
Form){if($form->getMethod()!==Form::POST){throw
new
InvalidStateException('File upload requires method POST.');}$form->getElementPrototype()->enctype='multipart/form-data';}parent::attached($form);}public
function
setValue($value){if(is_array($value)){$this->value=new
HttpUploadedFile($value);}elseif($value
instanceof
HttpUploadedFile){$this->value=$value;}else{$this->value=new
HttpUploadedFile(NULL);}return$this;}public
static
function
validateFilled(IFormControl$control){$file=$control->getValue();return$file
instanceof
HttpUploadedFile&&$file->isOK();}public
static
function
validateFileSize(FileUpload$control,$limit){$file=$control->getValue();return$file
instanceof
HttpUploadedFile&&$file->getSize()<=$limit;}public
static
function
validateMimeType(FileUpload$control,$mimeType){$file=$control->getValue();if($file
instanceof
HttpUploadedFile){$type=$file->getContentType();$type=strtolower(preg_replace('#\s*;.*$#','',$type));if(!$type){return
FALSE;}$mimeTypes=is_array($mimeType)?$mimeType:explode(',',$mimeType);if(in_array($type,$mimeTypes,TRUE)){return
TRUE;}if(in_array(preg_replace('#/.*#','/*',$type),$mimeTypes,TRUE)){return
TRUE;}}return
FALSE;}}class
HiddenField
extends
FormControl{private$forcedValue;public
function
__construct($forcedValue=NULL){parent::__construct();$this->control->type='hidden';$this->value=(string)$forcedValue;$this->forcedValue=$forcedValue;}public
function
getLabel($caption=NULL){return
NULL;}public
function
setValue($value){$this->value=is_scalar($value)?(string)$value:'';return$this;}public
function
getControl(){return
parent::getControl()->value($this->forcedValue===NULL?$this->value:$this->forcedValue);}}class
SubmitButton
extends
Button
implements
ISubmitterControl{public$onClick;public$onInvalidClick;private$validationScope=TRUE;public
function
__construct($caption=NULL){parent::__construct($caption);$this->control->type='submit';}public
function
setValue($value){$this->value=is_scalar($value)&&(bool)$value;$form=$this->getForm();if($this->value||!is_object($form->isSubmitted())){$this->value=TRUE;$form->setSubmittedBy($this);}return$this;}public
function
isSubmittedBy(){return$this->getForm()->isSubmitted()===$this;}public
function
setValidationScope($scope){$this->validationScope=(bool)$scope;return$this;}final
public
function
getValidationScope(){return$this->validationScope;}public
function
click(){$this->onClick($this);}public
static
function
validateSubmitted(ISubmitterControl$control){return$control->isSubmittedBy();}}class
ImageButton
extends
SubmitButton{public
function
__construct($src=NULL,$alt=NULL){parent::__construct();$this->control->type='image';$this->control->src=$src;$this->control->alt=$alt;}public
function
getHtmlName(){$name=parent::getHtmlName();return
strpos($name,'[')===FALSE?$name:$name.'[]';}public
function
loadHttpData(){$path=$this->getHtmlName();$path=explode('[',strtr(str_replace(']','',strpos($path,'[')===FALSE?$path.'.x':substr($path,0,-2)),'.','_'));$this->setValue(ArrayTools::get($this->getForm()->getHttpData(),$path)!==NULL);}}class
SelectBox
extends
FormControl{private$items=array();protected$allowed=array();private$skipFirst=FALSE;private$useKeys=TRUE;public
function
__construct($label=NULL,array$items=NULL,$size=NULL){parent::__construct($label);$this->control->setName('select');$this->control->size=$size>1?(int)$size:NULL;$this->control->onfocus='this.onmousewheel=function(){return false}';$this->label->onclick='return false';if($items!==NULL){$this->setItems($items);}}public
function
getValue(){$allowed=$this->allowed;if($this->skipFirst){$allowed=array_slice($allowed,1,count($allowed),TRUE);}return
is_scalar($this->value)&&isset($allowed[$this->value])?$this->value:NULL;}public
function
getRawValue(){return
is_scalar($this->value)?$this->value:NULL;}public
function
skipFirst($item=NULL){if(is_bool($item)){$this->skipFirst=$item;}else{$this->skipFirst=TRUE;if($item!==NULL){$this->items=array(''=>$item)+$this->items;$this->allowed=array(''=>'')+$this->allowed;}}return$this;}final
public
function
isFirstSkipped(){return$this->skipFirst;}final
public
function
areKeysUsed(){return$this->useKeys;}public
function
setItems(array$items,$useKeys=TRUE){$this->items=$items;$this->allowed=array();$this->useKeys=(bool)$useKeys;foreach($items
as$key=>$value){if(!is_array($value)){$value=array($key=>$value);}foreach($value
as$key2=>$value2){if(!$this->useKeys){if(!is_scalar($value2)){throw
new
InvalidArgumentException("All items must be scalars.");}$key2=$value2;}if(isset($this->allowed[$key2])){throw
new
InvalidArgumentException("Items contain duplication for key '$key2'.");}$this->allowed[$key2]=$value2;}}return$this;}final
public
function
getItems(){return$this->items;}public
function
getSelectedItem(){if(!$this->useKeys){return$this->getValue();}else{$value=$this->getValue();return$value===NULL?NULL:$this->allowed[$value];}}public
function
getControl(){$control=parent::getControl();$selected=$this->getValue();$selected=is_array($selected)?array_flip($selected):array($selected=>TRUE);$option=Html::el('option');foreach($this->items
as$key=>$value){if(!is_array($value)){$value=array($key=>$value);$dest=$control;}else{$dest=$control->create('optgroup')->label($key);}foreach($value
as$key2=>$value2){if($value2
instanceof
Html){$dest->add((string)$value2->selected(isset($selected[$key2])));}elseif($this->useKeys){$dest->add((string)$option->value($key2)->selected(isset($selected[$key2]))->setText($this->translate($value2)));}else{$dest->add((string)$option->selected(isset($selected[$value2]))->setText($this->translate($value2)));}}}return$control;}public
static
function
validateFilled(IFormControl$control){$value=$control->getValue();return
is_array($value)?count($value)>0:$value!==NULL;}}class
MultiSelectBox
extends
SelectBox{public
function
getValue(){$allowed=array_keys($this->allowed);if($this->isFirstSkipped()){unset($allowed[0]);}return
array_intersect($this->getRawValue(),$allowed);}public
function
getRawValue(){if(is_scalar($this->value)){$value=array($this->value);}elseif(!is_array($this->value)){$value=array();}else{$value=$this->value;}$res=array();foreach($value
as$val){if(is_scalar($val)){$res[]=$val;}}return$res;}public
function
getSelectedItem(){if(!$this->useKeys){return$this->getValue();}else{$res=array();foreach($this->getValue()as$value){$res[$value]=$this->allowed[$value];}return$res;}}public
function
getControl(){$control=parent::getControl();$control->name.='[]';$control->multiple=TRUE;return$control;}}class
RadioList
extends
FormControl{protected$separator;protected$container;protected$items=array();public
function
__construct($label=NULL,array$items=NULL){parent::__construct($label);$this->control->type='radio';$this->container=Html::el();$this->separator=Html::el('br');if($items!==NULL)$this->setItems($items);}public
function
getValue($raw=FALSE){return
is_scalar($this->value)&&($raw||isset($this->items[$this->value]))?$this->value:NULL;}public
function
setItems(array$items){$this->items=$items;return$this;}final
public
function
getItems(){return$this->items;}final
public
function
getSeparatorPrototype(){return$this->separator;}final
public
function
getContainerPrototype(){return$this->container;}public
function
getControl($key=NULL){if($key===NULL){$container=clone$this->container;$separator=(string)$this->separator;}elseif(!isset($this->items[$key])){return
NULL;}$control=parent::getControl();$id=$control->id;$counter=-1;$value=$this->value===NULL?NULL:(string)$this->getValue();$label=Html::el('label');foreach($this->items
as$k=>$val){$counter++;if($key!==NULL&&$key!=$k)continue;$control->id=$label->for=$id.'-'.$counter;$control->checked=(string)$k===$value;$control->value=$k;if($val
instanceof
Html){$label->setHtml($val);}else{$label->setText($this->translate($val));}if($key!==NULL){return(string)$control.(string)$label;}$container->add((string)$control.(string)$label.$separator);}return$container;}public
function
getLabel($caption=NULL){$label=parent::getLabel($caption);$label->for=NULL;return$label;}public
static
function
validateFilled(IFormControl$control){return$control->getValue()!==NULL;}}class
RepeaterControl
extends
FormContainer{public$repeatCount=3;public$repeatMin=1;public$repeatMax=0;protected$value;public
function
__construct(){throw
new
NotImplementedException;}public
function
setValue($value){if(is_array($value)){$this->value=$value;}else{$this->value=array();}return$this;}public
function
getValue(){return$this->value;}public
function
loadHttpData(){$name=$this->getName();$this->setValue(isset($data[$name])?$data[$name]:array());}}abstract
class
TextBase
extends
FormControl{protected$emptyValue='';protected$filters=array();public
function
setValue($value){$this->value=is_scalar($value)?(string)$value:'';return$this;}public
function
getValue(){$value=$this->value;foreach($this->filters
as$filter){$value=(string)call_user_func($filter,$value);}return$value===$this->translate($this->emptyValue)?'':$value;}public
function
setEmptyValue($value){$this->emptyValue=$value;return$this;}final
public
function
getEmptyValue(){return$this->emptyValue;}public
function
addFilter($filter){fixCallback($filter);if(!is_callable($filter)){$able=is_callable($filter,TRUE,$textual);throw
new
InvalidArgumentException("Filter '$textual' is not ".($able?'callable.':'valid PHP callback.'));}$this->filters[]=$filter;return$this;}public
function
notifyRule(Rule$rule){if(is_string($rule->operation)&&strcasecmp($rule->operation,':float')===0){$this->addFilter(array(__CLASS__,'filterFloat'));}parent::notifyRule($rule);}public
static
function
validateMinLength(TextBase$control,$length){return
iconv_strlen($control->getValue(),'UTF-8')>=$length;}public
static
function
validateMaxLength(TextBase$control,$length){return
iconv_strlen($control->getValue(),'UTF-8')<=$length;}public
static
function
validateLength(TextBase$control,$range){if(!is_array($range)){$range=array($range,$range);}$len=iconv_strlen($control->getValue(),'UTF-8');return($range[0]===NULL||$len>=$range[0])&&($range[1]===NULL||$len<=$range[1]);}public
static
function
validateEmail(TextBase$control){$atom="[-a-z0-9!#$%&'*+/=?^_`{|}~]";$localPart="(\"([ !\\x23-\\x5B\\x5D-\\x7E]*|\\\\[ -~])+\"|$atom+(\\.$atom+)*)";$chars="a-z0-9\x80-\xFF";$domain="[$chars]([-$chars]{0,61}[$chars])";return(bool)preg_match("(^$localPart@($domain?\\.)+[a-z]{2,14}\\z)i",$control->getValue());}public
static
function
validateUrl(TextBase$control){return(bool)preg_match('/^.+\.[a-z]{2,6}(\\/.*)?$/i',$control->getValue());}public
static
function
validateRegexp(TextBase$control,$regexp){return(bool)preg_match($regexp,$control->getValue());}public
static
function
validateInteger(TextBase$control){return(bool)preg_match('/^-?[0-9]+$/',$control->getValue());}public
static
function
validateFloat(TextBase$control){return(bool)preg_match('/^-?[0-9]*[.,]?[0-9]+$/',$control->getValue());}public
static
function
validateRange(TextBase$control,$range){return($range[0]===NULL||$control->getValue()>=$range[0])&&($range[1]===NULL||$control->getValue()<=$range[1]);}public
static
function
filterFloat($s){return
str_replace(array(' ',','),array('','.'),$s);}}class
TextArea
extends
TextBase{public
function
__construct($label=NULL,$cols=NULL,$rows=NULL){parent::__construct($label);$this->control->setName('textarea');$this->control->cols=$cols;$this->control->rows=$rows;$this->value='';}public
function
getControl(){$control=parent::getControl();$control->setText($this->getValue()===''?$this->translate($this->emptyValue):$this->value);return$control;}}class
TextInput
extends
TextBase{public
function
__construct($label=NULL,$cols=NULL,$maxLength=NULL){parent::__construct($label);$this->control->type='text';$this->control->size=$cols;$this->control->maxlength=$maxLength;$this->filters[]=array('String','trim');$this->filters[]=array($this,'checkMaxLength');$this->value='';}protected
function
checkMaxLength($value){if($this->control->maxlength&&iconv_strlen($value,'UTF-8')>$this->control->maxlength){$value=iconv_substr($value,0,$this->control->maxlength,'UTF-8');}return$value;}public
function
setPasswordMode($mode=TRUE){$this->control->type=$mode?'password':'text';return$this;}public
function
getControl(){$control=parent::getControl();if($this->control->type!=='password'){$control->value=$this->getValue()===''?$this->translate($this->emptyValue):$this->value;}return$control;}public
function
notifyRule(Rule$rule){if(is_string($rule->operation)&&strcasecmp($rule->operation,':length')===0&&!$rule->isNegative){$this->control->maxlength=is_array($rule->arg)?$rule->arg[1]:$rule->arg;}elseif(is_string($rule->operation)&&strcasecmp($rule->operation,':maxLength')===0&&!$rule->isNegative){$this->control->maxlength=$rule->arg;}parent::notifyRule($rule);}}class
ConventionalRenderer
extends
Object
implements
IFormRenderer{public$wrappers=array('form'=>array('container'=>NULL,'errors'=>TRUE),'error'=>array('container'=>'ul class=error','item'=>'li'),'group'=>array('container'=>'fieldset','label'=>'legend','description'=>'p'),'controls'=>array('container'=>'table'),'pair'=>array('container'=>'tr','.required'=>'required','.optional'=>NULL,'.odd'=>NULL),'control'=>array('container'=>'td','.odd'=>NULL,'errors'=>FALSE,'description'=>'small','requiredsuffix'=>'','.required'=>'required','.text'=>'text','.password'=>'text','.file'=>'text','.submit'=>'button','.image'=>'imagebutton','.button'=>'button'),'label'=>array('container'=>'th','suffix'=>NULL,'requiredsuffix'=>''),'hidden'=>array('container'=>'div'));protected$form;protected$clientScript=TRUE;protected$counter;public
function
render(Form$form,$mode=NULL){if($this->form!==$form){$this->form=$form;$this->init();}$s='';if(!$mode||$mode==='begin'){$s.=$this->renderBegin();}if((!$mode&&$this->getValue('form errors'))||$mode==='errors'){$s.=$this->renderErrors();}if(!$mode||$mode==='body'){$s.=$this->renderBody();}if(!$mode||$mode==='end'){$s.=$this->renderEnd();}return$s;}public
function
setClientScript($clientScript=NULL){$this->clientScript=$clientScript;return$this;}public
function
getClientScript(){if($this->clientScript===TRUE){$this->clientScript=new
InstantClientScript($this->form);}return$this->clientScript;}protected
function
init(){$clientScript=$this->getClientScript();if($clientScript!==NULL){$clientScript->enable();}$wrapper=&$this->wrappers['control'];foreach($this->form->getControls()as$control){if($control->getOption('required')&&isset($wrapper['.required'])){$control->getLabelPrototype()->class($wrapper['.required'],TRUE);}$el=$control->getControlPrototype();if($el->getName()==='input'&&isset($wrapper['.'.$el->type])){$el->class($wrapper['.'.$el->type],TRUE);}}}public
function
renderBegin(){$this->counter=0;foreach($this->form->getControls()as$control){$control->setOption('rendered',FALSE);}if(strcasecmp($this->form->getMethod(),'get')===0){$el=clone$this->form->getElementPrototype();$uri=explode('?',(string)$el->action,2);$el->action=$uri[0];$s='';if(isset($uri[1])){foreach(preg_split('#[;&]#',$uri[1])as$param){$parts=explode('=',$param,2);$name=urldecode($parts[0]);if(!isset($this->form[$name])){$s.=Html::el('input',array('type'=>'hidden','name'=>$name,'value'=>urldecode($parts[1])));}}$s="\n\t".$this->getWrapper('hidden container')->setHtml($s);}return$el->startTag().$s;}else{return$this->form->getElementPrototype()->startTag();}}public
function
renderEnd(){$s='';foreach($this->form->getControls()as$control){if($control
instanceof
HiddenField&&!$control->getOption('rendered')){$s.=(string)$control->getControl();}}if($s){$s=$this->getWrapper('hidden container')->setHtml($s)."\n";}$s.=$this->form->getElementPrototype()->endTag()."\n";$clientScript=$this->getClientScript();if($clientScript!==NULL){$s.=$clientScript->renderClientScript()."\n";}return$s;}public
function
renderErrors(IFormControl$control=NULL){$errors=$control===NULL?$this->form->getErrors():$control->getErrors();if(count($errors)){$ul=$this->getWrapper('error container');$li=$this->getWrapper('error item');foreach($errors
as$error){$item=clone$li;if($error
instanceof
Html){$item->add($error);}else{$item->setText($error);}$ul->add($item);}return"\n".$ul->render(0);}}public
function
renderBody(){$s=$remains='';$defaultContainer=$this->getWrapper('group container');$translator=$this->form->getTranslator();foreach($this->form->getGroups()as$group){if(!$group->getControls()||!$group->getOption('visual'))continue;$container=$group->getOption('container',$defaultContainer);$container=$container
instanceof
Html?clone$container:Html::el($container);$s.="\n".$container->startTag();$text=$group->getOption('label');if($text
instanceof
Html){$s.=$text;}elseif(is_string($text)){if($translator!==NULL){$text=$translator->translate($text);}$s.="\n".$this->getWrapper('group label')->setText($text)."\n";}$text=$group->getOption('description');if($text
instanceof
Html){$s.=$text;}elseif(is_string($text)){if($translator!==NULL){$text=$translator->translate($text);}$s.=$this->getWrapper('group description')->setText($text)."\n";}$s.=$this->renderControls($group);$remains=$container->endTag()."\n".$remains;if(!$group->getOption('embedNext')){$s.=$remains;$remains='';}}$s.=$remains.$this->renderControls($this->form);$container=$this->getWrapper('form container');$container->setHtml($s);return$container->render(0);}public
function
renderControls($parent){if(!($parent
instanceof
FormContainer||$parent
instanceof
FormGroup)){throw
new
InvalidArgumentException("Argument must be FormContainer or FormGroup instance.");}$container=$this->getWrapper('controls container');$buttons=NULL;foreach($parent->getControls()as$control){if($control->getOption('rendered')||$control
instanceof
HiddenField||$control->getForm(FALSE)!==$this->form){}elseif($control
instanceof
Button){$buttons[]=$control;}else{if($buttons){$container->add($this->renderPairMulti($buttons));$buttons=NULL;}$container->add($this->renderPair($control));}}if($buttons){$container->add($this->renderPairMulti($buttons));}$s='';if(count($container)){$s.="\n".$container."\n";}return$s;}public
function
renderPair(IFormControl$control){$pair=$this->getWrapper('pair container');$pair->add($this->renderLabel($control));$pair->add($this->renderControl($control));$pair->class($this->getValue($control->getOption('required')?'pair .required':'pair .optional'),TRUE);$pair->class($control->getOption('class'),TRUE);if(++$this->counter
%
2)$pair->class($this->getValue('pair .odd'),TRUE);$pair->id=$control->getOption('id');return$pair->render(0);}public
function
renderPairMulti(array$controls){$s=array();foreach($controls
as$control){if(!($control
instanceof
IFormControl)){throw
new
InvalidArgumentException("Argument must be array of IFormControl instances.");}$s[]=(string)$control->getControl();}$pair=$this->getWrapper('pair container');$pair->add($this->renderLabel($control));$pair->add($this->getWrapper('control container')->setHtml(implode(" ",$s)));return$pair->render(0);}public
function
renderLabel(IFormControl$control){$head=$this->getWrapper('label container');if($control
instanceof
Checkbox||$control
instanceof
Button){return$head->setHtml(($head->getName()==='td'||$head->getName()==='th')?'&nbsp;':'');}else{$label=$control->getLabel();$suffix=$this->getValue('label suffix').($control->getOption('required')?$this->getValue('label requiredsuffix'):'');if($label
instanceof
Html){$label->setHtml($label->getHtml().$suffix);$suffix='';}return$head->setHtml((string)$label.$suffix);}}public
function
renderControl(IFormControl$control){$body=$this->getWrapper('control container');if($this->counter
%
2)$body->class($this->getValue('control .odd'),TRUE);$description=$control->getOption('description');if($description
instanceof
Html){$description=' '.$control->getOption('description');}elseif(is_string($description)){$description=' '.$this->getWrapper('control description')->setText($description);}else{$description='';}if($control->getOption('required')){$description=$this->getValue('control requiredsuffix').$description;}if($this->getValue('control errors')){$description.=$this->renderErrors($control);}if($control
instanceof
Checkbox||$control
instanceof
Button){return$body->setHtml((string)$control->getControl().(string)$control->getLabel().$description);}else{return$body->setHtml((string)$control->getControl().$description);}}protected
function
getWrapper($name){$data=$this->getValue($name);return$data
instanceof
Html?clone$data:Html::el($data);}protected
function
getValue($name){$name=explode(' ',$name);$data=&$this->wrappers[$name[0]][$name[1]];return$data;}}final
class
InstantClientScript
extends
Object{public$validateFunction;public$toggleFunction;public$doAlert='if (element) element.focus(); alert(message);';public$doToggle='if (element) element.style.display = visible ? "" : "none";';public$validateScript;public$toggleScript;private$central;private$form;public
function
__construct(Form$form){$this->form=$form;$name=ucfirst($form->getName());$this->validateFunction='validate'.$name;$this->toggleFunction='toggle'.$name;}public
function
enable(){$this->validateScript='';$this->toggleScript='';$this->central=TRUE;foreach($this->form->getControls()as$control){$script=$this->getValidateScript($control->getRules());if($script){$this->validateScript.="do {\n\t$script} while(0);\n\n\t";}$this->toggleScript.=$this->getToggleScript($control->getRules());if($control
instanceof
ISubmitterControl&&$control->getValidationScope()!==TRUE){$this->central=FALSE;}}if($this->validateScript||$this->toggleScript){if($this->central){$this->form->getElementPrototype()->onsubmit("return $this->validateFunction(this)",TRUE);}else{foreach($this->form->getComponents(TRUE,'Nette\Forms\ISubmitterControl')as$control){if($control->getValidationScope()){$control->getControlPrototype()->onclick("return $this->validateFunction(this)",TRUE);}}}}}public
function
renderClientScript(){$s='';if($this->validateScript){$s.="function $this->validateFunction(sender) {\n\t"."var element, message, res;\n\t".$this->validateScript."return true;\n"."}\n\n";}if($this->toggleScript){$s.="function $this->toggleFunction(sender) {\n\t"."var element, visible, res;\n\t".$this->toggleScript."\n}\n\n"."$this->toggleFunction(null);\n";}if($s){return"<script type=\"text/javascript\">\n"."/* <![CDATA[ */\n".$s."/* ]]> */\n"."</script>";}}private
function
getValidateScript(Rules$rules,$onlyCheck=FALSE){$res='';foreach($rules
as$rule){if(!is_string($rule->operation))continue;if(strcasecmp($rule->operation,'Nette\Forms\InstantClientScript::javascript')===0){$res.="$rule->arg\n\t";continue;}$script=$this->getClientScript($rule->control,$rule->operation,$rule->arg);if(!$script)continue;if(!empty($rule->message)){if($onlyCheck){$res.="$script\n\tif (".($rule->isNegative?'':'!')."res) { return false; }\n\t";}else{$res.="$script\n\t"."if (".($rule->isNegative?'':'!')."res) { "."message = ".json_encode((string)vsprintf($rule->control->translate($rule->message),(array)$rule->arg))."; ".$this->doAlert." return false; }\n\t";}}if($rule->type===Rule::CONDITION){$innerScript=$this->getValidateScript($rule->subRules,$onlyCheck);if($innerScript){$res.="$script\n\tif (".($rule->isNegative?'!':'')."res) {\n\t\t".str_replace("\n\t","\n\t\t",rtrim($innerScript))."\n\t}\n\t";if(!$onlyCheck&&$rule->control
instanceof
ISubmitterControl){$this->central=FALSE;}}}}return$res;}private
function
getToggleScript(Rules$rules,$cond=NULL){$s='';foreach($rules->getToggles()as$id=>$visible){$s.="visible = true; {$cond}element = document.getElementById('".$id."');\n\t".($visible?'':'visible = !visible; ').$this->doToggle."\n\t";}foreach($rules
as$rule){if($rule->type===Rule::CONDITION&&is_string($rule->operation)){$script=$this->getClientScript($rule->control,$rule->operation,$rule->arg);if($script){$res=$this->getToggleScript($rule->subRules,$cond."$script visible = visible && ".($rule->isNegative?'!':'')."res;\n\t");if($res){$el=$rule->control->getControlPrototype();if($el->getName()==='select'){$el->onchange("$this->toggleFunction(this)",TRUE);}else{$el->onclick("$this->toggleFunction(this)",TRUE);}$s.=$res;}}}}return$s;}private
function
getValueScript(IFormControl$control){$tmp="element = document.getElementById(".json_encode($control->getHtmlId()).");\n\t";switch(TRUE){case$control
instanceof
Checkbox:return$tmp."var val = element.checked;\n\t";case$control
instanceof
RadioList:return"for (var val=null, i=0; i<".count($control->getItems())."; i++) {\n\t\t"."element = document.getElementById(".json_encode($control->getHtmlId().'-')."+i);\n\t\t"."if (element.checked) { val = element.value; break; }\n\t"."}\n\t";default:return$tmp."var val = element.value.replace(/^\\s+|\\s+\$/g, '');\n\t";}}private
function
getClientScript(IFormControl$control,$operation,$arg){$operation=strtolower($operation);switch(TRUE){case$control
instanceof
HiddenField||$control->isDisabled():return
NULL;case$operation===':filled'&&$control
instanceof
RadioList:return$this->getValueScript($control)."res = val !== null;";case$operation===':submitted'&&$control
instanceof
SubmitButton:return"element=null; res=sender && sender.name==".json_encode($control->getHtmlName()).";";case$operation===':equal'&&$control
instanceof
MultiSelectBox:$tmp=array();foreach((is_array($arg)?$arg:array($arg))as$item){$tmp[]="element.options[i].value==".json_encode((string)$item);}$first=$control->isFirstSkipped()?1:0;return"element = document.getElementById(".json_encode($control->getHtmlId()).");\n\tres = false;\n\t"."for (var i=$first;i<element.options.length;i++)\n\t\t"."if (element.options[i].selected && (".implode(' || ',$tmp).")) { res = true; break; }";case$operation===':filled'&&$control
instanceof
SelectBox:return"element = document.getElementById(".json_encode($control->getHtmlId()).");\n\t"."res = element.selectedIndex >= ".($control->isFirstSkipped()?1:0).";";case$operation===':filled'&&$control
instanceof
TextBase:return$this->getValueScript($control)."res = val!='' && val!=".json_encode((string)$control->getEmptyValue()).";";case$operation===':minlength'&&$control
instanceof
TextBase:return$this->getValueScript($control)."res = val.length>=".(int)$arg.";";case$operation===':maxlength'&&$control
instanceof
TextBase:return$this->getValueScript($control)."res = val.length<=".(int)$arg.";";case$operation===':length'&&$control
instanceof
TextBase:if(!is_array($arg)){$arg=array($arg,$arg);}return$this->getValueScript($control)."res = ".($arg[0]===NULL?"true":"val.length>=".(int)$arg[0])." && ".($arg[1]===NULL?"true":"val.length<=".(int)$arg[1]).";";case$operation===':email'&&$control
instanceof
TextBase:return$this->getValueScript($control).'res = /^[^@\s]+@[^@\s]+\.[a-z]{2,10}$/i.test(val);';case$operation===':url'&&$control
instanceof
TextBase:return$this->getValueScript($control).'res = /^.+\.[a-z]{2,6}(\\/.*)?$/i.test(val);';case$operation===':regexp'&&$control
instanceof
TextBase:if(strncmp($arg,'/',1)){throw
new
InvalidStateException("Regular expression '$arg' must be JavaScript compatible.");}return$this->getValueScript($control)."res = $arg.test(val);";case$operation===':integer'&&$control
instanceof
TextBase:return$this->getValueScript($control)."res = /^-?[0-9]+$/.test(val);";case$operation===':float'&&$control
instanceof
TextBase:return$this->getValueScript($control)."res = /^-?[0-9]*[.,]?[0-9]+$/.test(val);";case$operation===':range'&&$control
instanceof
TextBase:return$this->getValueScript($control)."res = ".($arg[0]===NULL?"true":"parseFloat(val)>=".json_encode((float)$arg[0]))." && ".($arg[1]===NULL?"true":"parseFloat(val)<=".json_encode((float)$arg[1])).";";case$operation===':filled'&&$control
instanceof
FormControl:return$this->getValueScript($control)."res = val!='';";case$operation===':valid'&&$control
instanceof
FormControl:return$this->getValueScript($control)."res = function(){\n\t".$this->getValidateScript($control->getRules(),TRUE)."return true; }();";case$operation===':equal'&&$control
instanceof
FormControl:if($control
instanceof
Checkbox)$arg=(bool)$arg;$tmp=array();foreach((is_array($arg)?$arg:array($arg))as$item){if($item
instanceof
IFormControl){$tmp[]="val==function(){var element;".$this->getValueScript($item)."return val;}()";}else{$tmp[]="val==".json_encode($item);}}return$this->getValueScript($control)."res = (".implode(' || ',$tmp).");";}}public
static
function
javascript(){return
TRUE;}}class
UserClientScript
extends
Object{private$form;public
function
__construct(Form$form){$this->form=$form;}public
function
renderClientScript(){$this->form->getElementPrototype()->attrs['data-nette-rules']=json_encode($this->exportContainer($this->form));}public
function
exportContainer(FormContainer$container){$data=array();foreach($container->getComponents()as$name=>$control){if($control
instanceof
FormContainer){$data[$name]=$this->exportContainer($control);}elseif($control
instanceof
IFormControl){$data[$name]=$this->exportControl($control);}}return
array('class'=>$container->getClass(),'controls'=>$data);}private
function
exportControl(IFormControl$control){return$control->isDisabled()?NULL:array('class'=>$control->getClass(),'rules'=>$this->exportRules($control->getRules()),'opt'=>$control
instanceof
FormControl?$control->getOptions():NULL,'scope'=>$control
instanceof
ISubmitterControl?(bool)$control->getValidationScope():NULL);}private
function
exportRules(Rules$rules){$data=array();foreach($rules
as$rule){if(!is_string($rule->operation))continue;$data[]=array('class'=>$rule->control->getClass(),'op'=>$rule->operation,'neg'=>$rule->isNegative,'type'=>$rule->type,'msg'=>$rule->message,'id'=>$rule->control->getHtmlId(),'arg'=>$rule->arg
instanceof
FormControl?$rule->arg->getHtmlId():$rule->arg,'sub'=>$rule->subRules?$this->exportRules($rule->subRules):NULL);}return$data;}}final
class
SafeStream{const
PROTOCOL='safe';private$handle;private$filePath;private$tempFile;private$startPos=0;private$writeError=FALSE;public
static
function
register(){return
stream_wrapper_register(self::PROTOCOL,__CLASS__);}public
function
stream_open($path,$mode,$options,&$opened_path){$path=substr($path,strlen(self::PROTOCOL)+3);$flag=trim($mode,'rwax+');$mode=trim($mode,'tb');$use_path=(bool)(STREAM_USE_PATH&$options);$append=FALSE;switch($mode){case'r':case'r+':$handle=@fopen($path,$mode.$flag,$use_path);if(!$handle)return
FALSE;if(flock($handle,$mode=='r'?LOCK_SH:LOCK_EX)){$this->handle=$handle;return
TRUE;}fclose($handle);return
FALSE;case'a':case'a+':$append=TRUE;case'w':case'w+':$handle=@fopen($path,'r+'.$flag,$use_path);if($handle){if(flock($handle,LOCK_EX)){if($append){fseek($handle,0,SEEK_END);$this->startPos=ftell($handle);}else{ftruncate($handle,0);}$this->handle=$handle;return
TRUE;}fclose($handle);}$mode{0}='x';case'x':case'x+':if(file_exists($path))return
FALSE;$tmp='~~'.time().'.tmp';$handle=@fopen($path.$tmp,$mode.$flag,$use_path);if($handle){if(flock($handle,LOCK_EX)){$this->handle=$handle;if(!@rename($path.$tmp,$path)){$this->tempFile=realpath($path.$tmp);$this->filePath=substr($this->tempFile,0,-strlen($tmp));}return
TRUE;}fclose($handle);unlink($path.$tmp);}return
FALSE;default:trigger_error("Unsupported mode $mode",E_USER_WARNING);return
FALSE;}}public
function
stream_close(){if($this->writeError){ftruncate($this->handle,$this->startPos);}fclose($this->handle);if($this->tempFile){if(!@rename($this->tempFile,$this->filePath)){unlink($this->tempFile);}}}public
function
stream_read($length){return
fread($this->handle,$length);}public
function
stream_write($data){$len=strlen($data);$res=fwrite($this->handle,$data,$len);if($res!==$len){$this->writeError=TRUE;}return$res;}public
function
stream_tell(){return
ftell($this->handle);}public
function
stream_eof(){return
feof($this->handle);}public
function
stream_seek($offset,$whence){return
fseek($this->handle,$offset,$whence)===0;}public
function
stream_stat(){return
fstat($this->handle);}public
function
url_stat($path,$flags){$path=substr($path,strlen(self::PROTOCOL)+3);return($flags&STREAM_URL_STAT_LINK)?@lstat($path):@stat($path);}public
function
unlink($path){$path=substr($path,strlen(self::PROTOCOL)+3);return
unlink($path);}}final
class
LimitedScope{private
static$vars;final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
evaluate(){if(func_num_args()>1){extract(func_get_arg(1));}return
eval('?>'.func_get_arg(0));}public
static
function
load(){if(func_num_args()>1){extract(func_get_arg(1));}return include func_get_arg(0);}}abstract
class
AutoLoader
extends
Object{static
private$loaders=array();public
static$count=0;final
public
static
function
load($type){foreach(func_get_args()as$type){if(!class_exists($type)){throw
new
InvalidStateException("Unable to load class or interface '$type'.");}}}final
public
static
function
getLoaders(){return
array_values(self::$loaders);}public
function
register(){if(!function_exists('spl_autoload_register')){throw
new
RuntimeException('spl_autoload does not exist in this PHP installation.');}spl_autoload_register(array($this,'tryLoad'));self::$loaders[spl_object_hash($this)]=$this;}public
function
unregister(){unset(self::$loaders[spl_object_hash($this)]);return
spl_autoload_unregister(array($this,'tryLoad'));}abstract
public
function
tryLoad($type);}class
RobotLoader
extends
AutoLoader{public$scanDirs;public$ignoreDirs='.*, *.old, *.bak, *.tmp, temp';public$acceptFiles='*.php, *.php5';public$autoRebuild;private$list=array();private$timestamps;private$rebuilded=FALSE;private$acceptMask;private$ignoreMask;public
function
__construct(){if(!extension_loaded('tokenizer')){throw
new
Exception("PHP extension Tokenizer is not loaded.");}}public
function
register(){$cache=$this->getCache();$key=$this->getKey();if(isset($cache[$key])){$this->list=$cache[$key];}else{$this->rebuild();}if(isset($this->list[strtolower(__CLASS__)])&&class_exists('NetteLoader',FALSE)){NetteLoader::getInstance()->unregister();}parent::register();}public
function
tryLoad($type){$type=strtolower($type);if(isset($this->list[$type])){if($this->list[$type]!==FALSE){LimitedScope::load($this->list[$type]);self::$count++;}}else{$this->list[$type]=FALSE;if($this->autoRebuild===NULL){$this->autoRebuild=!$this->isProduction();}if($this->autoRebuild){$this->rebuild(FALSE);}if($this->list[$type]!==FALSE){LimitedScope::load($this->list[$type]);self::$count++;}}}public
function
rebuild($force=TRUE){$cache=$this->getCache();$key=$this->getKey();$this->acceptMask=self::wildcards2re($this->acceptFiles);$this->ignoreMask=self::wildcards2re($this->ignoreDirs);$this->timestamps=$cache[$key.'ts'];if($force||!$this->rebuilded){foreach(array_unique($this->scanDirs)as$dir){$this->scanDirectory($dir);}}$this->rebuilded=TRUE;$cache[$key]=$this->list;$cache[$key.'ts']=$this->timestamps;$this->timestamps=NULL;}public
function
addDirectory($path){foreach((array)$path
as$val){$real=realpath($val);if($real===FALSE){throw
new
DirectoryNotFoundException("Directory '$val' not found.");}$this->scanDirs[]=$real;}}public
function
addClass($class,$file){$class=strtolower($class);if(!empty($this->list[$class])&&$this->list[$class]!==$file){spl_autoload_call($class);throw
new
InvalidStateException("Ambiguous class '$class' resolution; defined in $file and in ".$this->list[$class].".");}$this->list[$class]=$file;}private
function
scanDirectory($dir){$iterator=dir($dir);if(!$iterator)return;$disallow=array();if(is_file($dir.'/netterobots.txt')){foreach(file($dir.'/netterobots.txt')as$s){if(preg_match('#^disallow\\s*:\\s*(\\S+)#i',$s,$m)){$disallow[trim($m[1],'/')]=TRUE;}}if(isset($disallow['']))return;}while(FALSE!==($entry=$iterator->read())){if($entry=='.'||$entry=='..'||isset($disallow[$entry]))continue;$path=$dir.DIRECTORY_SEPARATOR.$entry;if(is_dir($path)){if(!preg_match($this->ignoreMask,$entry)){$this->scanDirectory($path);}continue;}if(is_file($path)&&preg_match($this->acceptMask,$entry)){$time=filemtime($path);if(!isset($this->timestamps[$path])||$this->timestamps[$path]!==$time){$this->timestamps[$path]=$time;$this->scanScript($path);}}}$iterator->close();}private
function
scanScript($file){if(!defined('T_NAMESPACE')){define('T_NAMESPACE',-1);define('T_NS_SEPARATOR',-1);}$expected=FALSE;$namespace='';$level=0;$s=file_get_contents($file);if(preg_match('#//nette'.'loader=(\S*)#',$s,$matches)){foreach(explode(',',$matches[1])as$name){$this->addClass($name,$file);}return;}foreach(token_get_all($s)as$token){if(is_array($token)){switch($token[0]){case
T_NAMESPACE:case
T_CLASS:case
T_INTERFACE:$expected=$token[0];$name='';continue
2;case
T_COMMENT:case
T_DOC_COMMENT:case
T_WHITESPACE:continue
2;case
T_NS_SEPARATOR:case
T_STRING:if($expected){$name.=$token[1];}continue
2;}}if($expected){if($expected===T_NAMESPACE){$namespace=$name.'\\';}elseif($level===0){$this->addClass($namespace.$name,$file);}$expected=FALSE;}if(is_array($token)){if($token[0]===T_CURLY_OPEN||$token[0]===T_DOLLAR_OPEN_CURLY_BRACES){$level++;}}elseif($token==='{'){$level++;}elseif($token==='}'){$level--;}}}private
static
function
wildcards2re($wildcards){$mask=array();foreach(explode(',',$wildcards)as$wildcard){$wildcard=trim($wildcard);$wildcard=addcslashes($wildcard,'.\\+[^]$(){}=!><|:#');$wildcard=strtr($wildcard,array('*'=>'.*','?'=>'.'));$mask[]=$wildcard;}return'#^('.implode('|',$mask).')$#i';}protected
function
getCache(){return
Environment::getCache('Nette.RobotLoader');}protected
function
getKey(){return
md5("$this->ignoreDirs|$this->acceptFiles|".implode('|',$this->scanDirs));}protected
function
isProduction(){return
Environment::isProduction();}}class
SimpleLoader
extends
AutoLoader{public
function
tryLoad($type){if(strpbrk($type,'./;|')!==FALSE){throw
new
InvalidArgumentException("Invalid class/interface name '$type'.");}$file=strtr($type,'\\','/').'.php';@LimitedScope::load($file);self::$count++;}}interface
IMailer{function
send(Mail$mail);}class
MailMimePart
extends
Object{const
ENCODING_BASE64='base64';const
ENCODING_7BIT='7bit';const
ENCODING_8BIT='8bit';const
ENCODING_QUOTED_PRINTABLE='quoted-printable';const
EOL="\r\n";const
LINE_LENGTH=76;private$headers=array();private$parts=array();private$body;public
function
setHeader($name,$value,$append=FALSE){if(!$name||preg_match('#[^a-z0-9-]#i',$name)){throw
new
InvalidArgumentException("Header name must be non-empty alphanumeric string, '$name' given.");}if($value==NULL){if(!$append){unset($this->headers[$name]);}}elseif(is_array($value)){$tmp=&$this->headers[$name];if(!$append||!is_array($tmp)){$tmp=array();}foreach($value
as$email=>$name){if(!preg_match('#^[^@",\s]+@[^@",\s]+\.[a-z]{2,10}$#i',$email)){throw
new
InvalidArgumentException("Email address '$email' is not valid.");}if(preg_match('#[\r\n]#',$name)){throw
new
InvalidArgumentException("Name cannot contain the line separator.");}$tmp[$email]=$name;}}else{$this->headers[$name]=preg_replace('#[\r\n]+#',' ',$value);}return$this;}public
function
getHeader($name){return
isset($this->headers[$name])?$this->headers[$name]:NULL;}public
function
clearHeader($name){unset($this->headers[$name]);return$this;}public
function
getEncodedHeader($name,$charset='UTF-8'){$len=strlen($name)+2;if(!isset($this->headers[$name])){return
NULL;}elseif(is_array($this->headers[$name])){$s='';foreach($this->headers[$name]as$email=>$name){if($name!=NULL){$s.=self::encodeQuotedPrintableHeader(strspn($name,'.,;<@>()[]"=?')?'"'.addcslashes($name,'"\\').'"':$name,$charset,$len);$email=" <$email>";}if($len+strlen($email)+1>self::LINE_LENGTH){$s.=self::EOL."\t";$len=1;}$s.="$email,";$len+=strlen($email)+1;}return
substr($s,0,-1);}else{return
self::encodeQuotedPrintableHeader($this->headers[$name],$charset,$len);}}public
function
getHeaders(){return$this->headers;}public
function
setContentType($contentType,$charset=NULL){$this->setHeader('Content-Type',$contentType.($charset?"; charset=$charset":''));return$this;}public
function
setEncoding($encoding){$this->setHeader('Content-Transfer-Encoding',$encoding);return$this;}public
function
getEncoding(){return$this->getHeader('Content-Transfer-Encoding');}public
function
addPart(MailMimePart$part=NULL){return$this->parts[]=$part===NULL?new
self:$part;}public
function
setBody($body){$this->body=$body;return$this;}public
function
getBody(){return$this->body;}public
function
generateMessage(){$output='';$boundary='--------'.md5(uniqid('',TRUE));foreach($this->headers
as$name=>$value){$output.=$name.': '.$this->getEncodedHeader($name);if($this->parts&&$name==='Content-Type'){$output.=';'.self::EOL."\tboundary=\"$boundary\"";}$output.=self::EOL;}$output.=self::EOL;$body=(string)$this->body;if($body!==''){switch($this->getEncoding()){case
self::ENCODING_QUOTED_PRINTABLE:$output.=function_exists('quoted_printable_encode')?quoted_printable_encode($body):self::encodeQuotedPrintable($body);break;case
self::ENCODING_BASE64:$output.=rtrim(chunk_split(base64_encode($body),self::LINE_LENGTH,self::EOL));break;case
self::ENCODING_7BIT:$body=preg_replace('#[\x80-\xFF]+#','',$body);case
self::ENCODING_8BIT:$body=str_replace(array("\x00","\r"),'',$body);$body=str_replace("\n",self::EOL,$body);$output.=$body;break;default:throw
new
InvalidStateException('Unknown encoding');}}if($this->parts){if(substr($output,-strlen(self::EOL))!==self::EOL)$output.=self::EOL;foreach($this->parts
as$part){$output.='--'.$boundary.self::EOL.$part->generateMessage().self::EOL;}$output.='--'.$boundary.'--';}return$output;}private
static
function
encodeQuotedPrintableHeader($s,$charset='UTF-8',&$len=0){$range='!"#$%&\'()*+,-./0123456789:;<>@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^`abcdefghijklmnopqrstuvwxyz{|}';if(strspn($s,$range."=? _\r\n\t")===strlen($s)){return$s;}$prefix="=?$charset?Q?";$pos=0;$len+=strlen($prefix);$o=$prefix;$size=strlen($s);while($pos<$size){if($l=strspn($s,$range,$pos)){while($len+$l>self::LINE_LENGTH-2){$lx=self::LINE_LENGTH-$len-2;$o.=substr($s,$pos,$lx).'?='.self::EOL."\t".$prefix;$pos+=$lx;$l-=$lx;$len=strlen($prefix)+1;}$o.=substr($s,$pos,$l);$len+=$l;$pos+=$l;}else{$len+=3;if(($s[$pos]&"\xC0")!=="\x80"&&$len>self::LINE_LENGTH-2-9){$o.='?='.self::EOL."\t".$prefix;$len=strlen($prefix)+1+3;}$o.='='.strtoupper(bin2hex($s[$pos]));$pos++;}}return$o.'?=';}public
static
function
encodeQuotedPrintable($s){$range='!"#$%&\'()*+,-./0123456789:;<>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}';$pos=0;$len=0;$o='';$size=strlen($s);while($pos<$size){if($l=strspn($s,$range,$pos)){while($len+$l>self::LINE_LENGTH-1){$lx=self::LINE_LENGTH-$len-1;$o.=substr($s,$pos,$lx).'='.self::EOL;$pos+=$lx;$l-=$lx;$len=0;}$o.=substr($s,$pos,$l);$len+=$l;$pos+=$l;}else{$len+=3;if($len>self::LINE_LENGTH-1){$o.='='.self::EOL;$len=3;}$o.='='.strtoupper(bin2hex($s[$pos]));$pos++;}}return
rtrim($o,'='.self::EOL);}}class
Mail
extends
MailMimePart{const
HIGH=1;const
NORMAL=3;const
LOW=5;public
static$defaultMailer='Nette\Mail\SendmailMailer';public
static$defaultHeaders=array('MIME-Version'=>'1.0','X-Mailer'=>'Nette Framework');private$mailer;private$charset='UTF-8';private$attachments=array();private$inlines=array();private$html;private$basePath;public
function
__construct(){foreach(self::$defaultHeaders
as$name=>$value){$this->setHeader($name,$value);}$this->setHeader('Date',date('r'));}public
function
setFrom($email,$name=NULL){$this->setHeader('From',$this->formatEmail($email,$name));return$this;}public
function
getFrom(){return$this->getHeader('From');}public
function
addReplyTo($email,$name=NULL){$this->setHeader('Reply-To',$this->formatEmail($email,$name),TRUE);return$this;}public
function
setSubject($subject){$this->setHeader('Subject',$subject);return$this;}public
function
getSubject(){return$this->getHeader('Subject');}public
function
addTo($email,$name=NULL){$this->setHeader('To',$this->formatEmail($email,$name),TRUE);return$this;}public
function
addCc($email,$name=NULL){$this->setHeader('Cc',$this->formatEmail($email,$name),TRUE);return$this;}public
function
addBcc($email,$name=NULL){$this->setHeader('Bcc',$this->formatEmail($email,$name),TRUE);return$this;}private
function
formatEmail($email,$name){if(!$name&&preg_match('#^(.+) +<(.*)>$#',$email,$matches)){return
array($matches[2]=>$matches[1]);}else{return
array($email=>$name);}}public
function
setReturnPath($email){$this->setHeader('Return-Path',$email);return$this;}public
function
getReturnPath(){return$this->getHeader('From');}public
function
setPriority($priority){$this->setHeader('X-Priority',(int)$priority);return$this;}public
function
getPriority(){return$this->getHeader('X-Priority');}public
function
setHtmlBody($html,$basePath=NULL){$this->html=$html;$this->basePath=$basePath;return$this;}public
function
getHtmlBody(){return$this->html;}public
function
addEmbeddedFile($file,$content=NULL,$contentType=NULL){$part=new
MailMimePart;$part->setBody($content===NULL?$this->readFile($file,$contentType):(string)$content);$part->setContentType($contentType?$contentType:'application/octet-stream');$part->setEncoding(self::ENCODING_BASE64);$part->setHeader('Content-Disposition','inline; filename="'.basename($file).'"');$part->setHeader('Content-ID','<'.md5(uniqid('',TRUE)).'>');return$this->inlines[$file]=$part;}public
function
addAttachment($file,$content=NULL,$contentType=NULL){$part=new
MailMimePart;$part->setBody($content===NULL?$this->readFile($file,$contentType):(string)$content);$part->setContentType($contentType?$contentType:'application/octet-stream');$part->setEncoding(self::ENCODING_BASE64);$part->setHeader('Content-Disposition','attachment; filename="'.basename($file).'"');return$this->attachments[]=$part;}private
function
readFile($file,&$contentType){if(!is_file($file)){throw
new
FileNotFoundException("File '$file' not found.");}if(!$contentType&&$info=getimagesize($file)){$contentType=$info['mime'];}return
file_get_contents($file);}public
function
send(){$this->getMailer()->send($this->build());}public
function
setMailer(IMailer$mailer){$this->mailer=$mailer;return$this;}public
function
getMailer(){if($this->mailer===NULL){fixNamespace(self::$defaultMailer);$this->mailer=is_object(self::$defaultMailer)?self::$defaultMailer:new
self::$defaultMailer;}return$this->mailer;}protected
function
build(){$mail=clone$this;$hostname=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'localhost';$mail->setHeader('Message-ID','<'.md5(uniqid('',TRUE))."@$hostname>");$mail->buildHtml();$mail->buildText();$cursor=$mail;if($mail->attachments){$tmp=$cursor->setContentType('multipart/mixed');$cursor=$cursor->addPart();foreach($mail->attachments
as$value){$tmp->addPart($value);}}if($mail->html!=NULL){$tmp=$cursor->setContentType('multipart/alternative');$cursor=$cursor->addPart();$alt=$tmp->addPart();if($mail->inlines){$tmp=$alt->setContentType('multipart/related');$alt=$alt->addPart();foreach($mail->inlines
as$name=>$value){$tmp->addPart($value);}}$alt->setContentType('text/html',$mail->charset)->setEncoding(preg_match('#[\x80-\xFF]#',$mail->html)?self::ENCODING_8BIT:self::ENCODING_7BIT)->setBody($mail->html);}$text=$mail->getBody();$mail->setBody(NULL);$cursor->setContentType('text/plain',$mail->charset)->setEncoding(preg_match('#[\x80-\xFF]#',$text)?self::ENCODING_8BIT:self::ENCODING_7BIT)->setBody($text);return$mail;}protected
function
buildHtml(){if($this->html
instanceof
ITemplate){$this->html->mail=$this;if($this->basePath===NULL&&$this->html
instanceof
IFileTemplate){$this->basePath=dirname($this->html->getFile());}$this->html=$this->html->__toString(TRUE);}if($this->basePath!==FALSE){$cids=array();preg_match_all('#(src\s*=\s*|background\s*=\s*|url\()(["\'])(?![a-z]+:|[/\\#])(.+?)\\2#i',$this->html,$matches,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);foreach(array_reverse($matches)as$m){$file=rtrim($this->basePath,'/\\').'/'.$m[3][0];$cid=isset($cids[$file])?$cids[$file]:$cids[$file]=substr($this->addEmbeddedFile($file)->getHeader("Content-ID"),1,-1);$this->html=substr_replace($this->html,"{$m[1][0]}{$m[2][0]}cid:$cid{$m[2][0]}",$m[0][1],strlen($m[0][0]));}}if(!$this->getSubject()&&preg_match('#<title>(.+?)</title>#is',$this->html,$matches)){$this->setSubject(html_entity_decode($matches[1],ENT_QUOTES,$this->charset));}}protected
function
buildText(){$text=$this->getBody();if($text
instanceof
ITemplate){$text->mail=$this;$this->setBody($text->__toString(TRUE));}elseif($text==NULL&&$this->html!=NULL){$text=preg_replace('#<(style|script|head).*</\\1>#Uis','',$this->html);$text=preg_replace('#[ \t\r\n]+#',' ',$text);$text=preg_replace('#<(/?p|/?h\d|li|br|/tr)[ >]#i',"\n$0",$text);$text=html_entity_decode(strip_tags($text),ENT_QUOTES,$this->charset);$this->setBody(trim($text));}}}class
SendmailMailer
extends
Object
implements
IMailer{public
function
send(Mail$mail){$tmp=clone$mail;$tmp->setHeader('Subject',NULL);$tmp->setHeader('To',NULL);$parts=explode(Mail::EOL.Mail::EOL,$tmp->generateMessage(),2);$linux=strncasecmp(PHP_OS,'win',3);Tools::tryError();$res=mail($mail->getEncodedHeader('To'),$mail->getEncodedHeader('Subject'),$linux?str_replace(Mail::EOL,"\n",$parts[1]):$parts[1],$linux?str_replace(Mail::EOL,"\n",$parts[0]):$parts[0]);if(Tools::catchError($msg)){throw
new
InvalidStateException($msg);}elseif(!$res){throw
new
InvalidStateException('Unable to send email.');}}}class
AuthenticationException
extends
Exception{}interface
IAuthenticator{const
USERNAME='username';const
PASSWORD='password';const
IDENTITY_NOT_FOUND=1;const
INVALID_CREDENTIAL=2;const
FAILURE=3;const
NOT_APPROVED=4;function
authenticate(array$credentials);}interface
IAuthorizator{const
ALL=NULL;const
ALLOW=TRUE;const
DENY=FALSE;function
isAllowed($role=self::ALL,$resource=self::ALL,$privilege=self::ALL);}interface
IIdentity{function
getName();function
getRoles();}interface
IPermissionAssertion{public
function
assert(Permission$acl,$roleId,$resourceId,$privilege);}interface
IResource{public
function
getResourceId();}interface
IRole{public
function
getRoleId();}class
Identity
extends
FreezableObject
implements
IIdentity{private$name;private$roles;private$data;public
function
__construct($name,$roles=NULL,$data=NULL){$this->setName($name);$this->setRoles((array)$roles);$this->data=(array)$data;}public
function
setName($name){$this->updating();$this->name=(string)$name;return$this;}public
function
getName(){return$this->name;}public
function
setRoles(array$roles){$this->updating();$this->roles=$roles;return$this;}public
function
getRoles(){return$this->roles;}public
function
getData(){return$this->data;}public
function
__set($key,$value){$this->updating();if($key==='name'||$key==='roles'){parent::__set($key,$value);}else{$this->data[$key]=$value;}}public
function&__get($key){if($key==='name'||$key==='roles'){return
parent::__get($key);}else{return$this->data[$key];}}}class
Permission
extends
Object
implements
IAuthorizator{private$roles=array();private$resources=array();private$rules=array('allResources'=>array('allRoles'=>array('allPrivileges'=>array('type'=>self::DENY,'assert'=>NULL),'byPrivilege'=>array()),'byRole'=>array()),'byResource'=>array());private$queriedRole,$queriedResource;public
function
addRole($role,$parents=NULL){$this->checkRole($role,FALSE);if(isset($this->roles[$role])){throw
new
InvalidStateException("Role '$role' already exists in the list.");}$roleParents=array();if($parents!==NULL){if(!is_array($parents)){$parents=array($parents);}foreach($parents
as$parent){$this->checkRole($parent);$roleParents[$parent]=TRUE;$this->roles[$parent]['children'][$role]=TRUE;}}$this->roles[$role]=array('parents'=>$roleParents,'children'=>array());return$this;}public
function
hasRole($role){$this->checkRole($role,FALSE);return
isset($this->roles[$role]);}private
function
checkRole($role,$need=TRUE){if(!is_string($role)||$role===''){throw
new
InvalidArgumentException("Role must be a non-empty string.");}elseif($need&&!isset($this->roles[$role])){throw
new
InvalidStateException("Role '$role' does not exist.");}}public
function
getRoleParents($role){$this->checkRole($role);return
array_keys($this->roles[$role]['parents']);}public
function
roleInheritsFrom($role,$inherit,$onlyParents=FALSE){$this->checkRole($role);$this->checkRole($inherit);$inherits=isset($this->roles[$role]['parents'][$inherit]);if($inherits||$onlyParents){return$inherits;}foreach($this->roles[$role]['parents']as$parent=>$foo){if($this->roleInheritsFrom($parent,$inherit)){return
TRUE;}}return
FALSE;}public
function
removeRole($role){$this->checkRole($role);foreach($this->roles[$role]['children']as$child=>$foo)unset($this->roles[$child]['parents'][$role]);foreach($this->roles[$role]['parents']as$parent=>$foo)unset($this->roles[$parent]['children'][$role]);unset($this->roles[$role]);foreach($this->rules['allResources']['byRole']as$roleCurrent=>$rules){if($role===$roleCurrent){unset($this->rules['allResources']['byRole'][$roleCurrent]);}}foreach($this->rules['byResource']as$resourceCurrent=>$visitor){foreach($visitor['byRole']as$roleCurrent=>$rules){if($role===$roleCurrent){unset($this->rules['byResource'][$resourceCurrent]['byRole'][$roleCurrent]);}}}return$this;}public
function
removeAllRoles(){$this->roles=array();foreach($this->rules['allResources']['byRole']as$roleCurrent=>$rules)unset($this->rules['allResources']['byRole'][$roleCurrent]);foreach($this->rules['byResource']as$resourceCurrent=>$visitor){foreach($visitor['byRole']as$roleCurrent=>$rules){unset($this->rules['byResource'][$resourceCurrent]['byRole'][$roleCurrent]);}}return$this;}public
function
addResource($resource,$parent=NULL){$this->checkResource($resource,FALSE);if(isset($this->resources[$resource])){throw
new
InvalidStateException("Resource '$resource' already exists in the list.");}if($parent!==NULL){$this->checkResource($parent);$this->resources[$parent]['children'][$resource]=TRUE;}$this->resources[$resource]=array('parent'=>$parent,'children'=>array());return$this;}public
function
hasResource($resource){$this->checkResource($resource,FALSE);return
isset($this->resources[$resource]);}private
function
checkResource($resource,$need=TRUE){if(!is_string($resource)||$resource===''){throw
new
InvalidArgumentException("Resource must be a non-empty string.");}elseif($need&&!isset($this->resources[$resource])){throw
new
InvalidStateException("Resource '$resource' does not exist.");}}public
function
resourceInheritsFrom($resource,$inherit,$onlyParent=FALSE){$this->checkResource($resource);$this->checkResource($inherit);if($this->resources[$resource]['parent']===NULL){return
FALSE;}$parent=$this->resources[$resource]['parent'];if($inherit===$parent){return
TRUE;}elseif($onlyParent){return
FALSE;}while($this->resources[$parent]['parent']!==NULL){$parent=$this->resources[$parent]['parent'];if($inherit===$parent){return
TRUE;}}return
FALSE;}public
function
removeResource($resource){$this->checkResource($resource);$parent=$this->resources[$resource]['parent'];if($parent!==NULL){unset($this->resources[$parent]['children'][$resource]);}$removed=array($resource);foreach($this->resources[$resource]['children']as$child=>$foo){$this->removeResource($child);$removed[]=$child;}foreach($removed
as$resourceRemoved){foreach($this->rules['byResource']as$resourceCurrent=>$rules){if($resourceRemoved===$resourceCurrent){unset($this->rules['byResource'][$resourceCurrent]);}}}unset($this->resources[$resource]);return$this;}public
function
removeAllResources(){foreach($this->resources
as$resource=>$foo){foreach($this->rules['byResource']as$resourceCurrent=>$rules){if($resource===$resourceCurrent){unset($this->rules['byResource'][$resourceCurrent]);}}}$this->resources=array();return$this;}public
function
allow($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL,IPermissionAssertion$assertion=NULL){$this->setRule(TRUE,self::ALLOW,$roles,$resources,$privileges,$assertion);return$this;}public
function
deny($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL,IPermissionAssertion$assertion=NULL){$this->setRule(TRUE,self::DENY,$roles,$resources,$privileges,$assertion);return$this;}public
function
removeAllow($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL){$this->setRule(FALSE,self::ALLOW,$roles,$resources,$privileges);return$this;}public
function
removeDeny($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL){$this->setRule(FALSE,self::DENY,$roles,$resources,$privileges);return$this;}protected
function
setRule($toAdd,$type,$roles,$resources,$privileges,IPermissionAssertion$assertion=NULL){if($roles===self::ALL){$roles=array(self::ALL);}else{if(!is_array($roles)){$roles=array($roles);}foreach($roles
as$role){$this->checkRole($role);}}if($resources===self::ALL){$resources=array(self::ALL);}else{if(!is_array($resources)){$resources=array($resources);}foreach($resources
as$resource){$this->checkResource($resource);}}if($privileges===self::ALL){$privileges=array();}elseif(!is_array($privileges)){$privileges=array($privileges);}if($toAdd){foreach($resources
as$resource){foreach($roles
as$role){$rules=&$this->getRules($resource,$role,TRUE);if(count($privileges)===0){$rules['allPrivileges']['type']=$type;$rules['allPrivileges']['assert']=$assertion;if(!isset($rules['byPrivilege'])){$rules['byPrivilege']=array();}}else{foreach($privileges
as$privilege){$rules['byPrivilege'][$privilege]['type']=$type;$rules['byPrivilege'][$privilege]['assert']=$assertion;}}}}}else{foreach($resources
as$resource){foreach($roles
as$role){$rules=&$this->getRules($resource,$role);if($rules===NULL){continue;}if(count($privileges)===0){if($resource===self::ALL&&$role===self::ALL){if($type===$rules['allPrivileges']['type']){$rules=array('allPrivileges'=>array('type'=>self::DENY,'assert'=>NULL),'byPrivilege'=>array());}continue;}if($type===$rules['allPrivileges']['type']){unset($rules['allPrivileges']);}}else{foreach($privileges
as$privilege){if(isset($rules['byPrivilege'][$privilege])&&$type===$rules['byPrivilege'][$privilege]['type']){unset($rules['byPrivilege'][$privilege]);}}}}}}return$this;}public
function
isAllowed($role=self::ALL,$resource=self::ALL,$privilege=self::ALL){$this->queriedRole=$role;if($role!==self::ALL){if($role
instanceof
IRole){$role=$role->getRoleId();}$this->checkRole($role);}$this->queriedResource=$resource;if($resource!==self::ALL){if($resource
instanceof
IResource){$resource=$resource->getResourceId();}$this->checkResource($resource);}if($privilege===self::ALL){do{if($role!==NULL&&NULL!==($result=$this->roleDFSAllPrivileges($role,$resource))){break;}if(NULL!==($rules=$this->getRules($resource,self::ALL))){foreach($rules['byPrivilege']as$privilege=>$rule){if(self::DENY===($ruleTypeOnePrivilege=$this->getRuleType($resource,NULL,$privilege))){$result=self::DENY;break
2;}}if(NULL!==($ruleTypeAllPrivileges=$this->getRuleType($resource,NULL,NULL))){$result=self::ALLOW===$ruleTypeAllPrivileges;break;}}$resource=$this->resources[$resource]['parent'];}while(TRUE);}else{do{if($role!==NULL&&NULL!==($result=$this->roleDFSOnePrivilege($role,$resource,$privilege))){break;}if(NULL!==($ruleType=$this->getRuleType($resource,NULL,$privilege))){$result=self::ALLOW===$ruleType;break;}elseif(NULL!==($ruleTypeAllPrivileges=$this->getRuleType($resource,NULL,NULL))){$result=self::ALLOW===$ruleTypeAllPrivileges;break;}$resource=$this->resources[$resource]['parent'];}while(TRUE);}$this->queriedRole=$this->queriedResource=NULL;return$result;}public
function
getQueriedRole(){return$this->queriedRole;}public
function
getQueriedResource(){return$this->queriedResource;}private
function
roleDFSAllPrivileges($role,$resource){$dfs=array('visited'=>array(),'stack'=>array($role));while(NULL!==($role=array_pop($dfs['stack']))){if(!isset($dfs['visited'][$role])){if(NULL!==($result=$this->roleDFSVisitAllPrivileges($role,$resource,$dfs))){return$result;}}}return
NULL;}private
function
roleDFSVisitAllPrivileges($role,$resource,&$dfs){if(NULL!==($rules=$this->getRules($resource,$role))){foreach($rules['byPrivilege']as$privilege=>$rule){if(self::DENY===$this->getRuleType($resource,$role,$privilege)){return
self::DENY;}}if(NULL!==($type=$this->getRuleType($resource,$role,NULL))){return
self::ALLOW===$type;}}$dfs['visited'][$role]=TRUE;foreach($this->roles[$role]['parents']as$roleParent=>$foo){$dfs['stack'][]=$roleParent;}return
NULL;}private
function
roleDFSOnePrivilege($role,$resource,$privilege){$dfs=array('visited'=>array(),'stack'=>array($role));while(NULL!==($role=array_pop($dfs['stack']))){if(!isset($dfs['visited'][$role])){if(NULL!==($result=$this->roleDFSVisitOnePrivilege($role,$resource,$privilege,$dfs))){return$result;}}}return
NULL;}private
function
roleDFSVisitOnePrivilege($role,$resource,$privilege,&$dfs){if(NULL!==($type=$this->getRuleType($resource,$role,$privilege))){return
self::ALLOW===$type;}if(NULL!==($type=$this->getRuleType($resource,$role,NULL))){return
self::ALLOW===$type;}$dfs['visited'][$role]=TRUE;foreach($this->roles[$role]['parents']as$roleParent=>$foo)$dfs['stack'][]=$roleParent;return
NULL;}private
function
getRuleType($resource,$role,$privilege){if(NULL===($rules=$this->getRules($resource,$role))){return
NULL;}if($privilege===self::ALL){if(isset($rules['allPrivileges'])){$rule=$rules['allPrivileges'];}else{return
NULL;}}elseif(!isset($rules['byPrivilege'][$privilege])){return
NULL;}else{$rule=$rules['byPrivilege'][$privilege];}if($rule['assert']===NULL||$rule['assert']->assert($this,$role,$resource,$privilege)){return$rule['type'];}elseif($resource!==self::ALL||$role!==self::ALL||$privilege!==self::ALL){return
NULL;}elseif(self::ALLOW===$rule['type']){return
self::DENY;}else{return
self::ALLOW;}}private
function&getRules($resource,$role,$create=FALSE){if($resource===self::ALL){$visitor=&$this->rules['allResources'];}else{if(!isset($this->rules['byResource'][$resource])){if(!$create){$null=NULL;return$null;}$this->rules['byResource'][$resource]=array();}$visitor=&$this->rules['byResource'][$resource];}if($role===self::ALL){if(!isset($visitor['allRoles'])){if(!$create){$null=NULL;return$null;}$visitor['allRoles']['byPrivilege']=array();}return$visitor['allRoles'];}if(!isset($visitor['byRole'][$role])){if(!$create){$null=NULL;return$null;}$visitor['byRole'][$role]['byPrivilege']=array();}return$visitor['byRole'][$role];}}class
SimpleAuthenticator
extends
Object
implements
IAuthenticator{private$userlist;public
function
__construct(array$userlist){$this->userlist=$userlist;}public
function
authenticate(array$credentials){$username=$credentials[self::USERNAME];foreach($this->userlist
as$name=>$pass){if(strcasecmp($name,$credentials[self::USERNAME])===0){if(strcasecmp($pass,$credentials[self::PASSWORD])===0){return
new
Identity($name);}throw
new
AuthenticationException("Invalid password.",self::INVALID_CREDENTIAL);}}throw
new
AuthenticationException("User '$username' not found.",self::IDENTITY_NOT_FOUND);}}interface
ITemplate{function
render();}abstract
class
BaseTemplate
extends
Object
implements
ITemplate{public$warnOnUndefined=TRUE;public$onPrepareFilters=array();private$params=array();private$filters=array();private$helpers=array();private$helperLoaders=array();public
function
registerFilter($callback){fixCallback($callback);if(!is_callable($callback)){$able=is_callable($callback,TRUE,$textual);throw
new
InvalidArgumentException("Filter '$textual' is not ".($able?'callable.':'valid PHP callback.'));}if(in_array($callback,$this->filters)){is_callable($callback,TRUE,$textual);throw
new
InvalidStateException("Filter '$textual' was registered twice.");}$this->filters[]=$callback;}final
public
function
getFilters(){return$this->filters;}public
function
render(){}public
function
__toString(){ob_start();try{$this->render();return
ob_get_clean();}catch(Exception$e){ob_end_clean();if(func_num_args()&&func_get_arg(0)){throw$e;}else{trigger_error($e->getMessage(),E_USER_WARNING);return'';}}}protected
function
compile($content,$label=NULL){if(!$this->filters){$this->onPrepareFilters($this);}try{foreach($this->filters
as$filter){$content=self::extractPhp($content,$blocks);$content=call_user_func($filter,$content);$content=strtr($content,$blocks);}}catch(Exception$e){is_callable($filter,TRUE,$textual);throw
new
InvalidStateException("Filter $textual: ".$e->getMessage().($label?" (in $label)":''),0,$e);}if($label){$content="<?php\n// $label\n//\n?>$content";}return
self::optimizePhp($content);}public
function
registerHelper($name,$callback){fixCallback($callback);if(!is_callable($callback)){$able=is_callable($callback,TRUE,$textual);throw
new
InvalidArgumentException("Helper handler '$textual' is not ".($able?'callable.':'valid PHP callback.'));}$this->helpers[strtolower($name)]=$callback;}public
function
registerHelperLoader($callback){fixCallback($callback);if(!is_callable($callback)){$able=is_callable($callback,TRUE,$textual);throw
new
InvalidArgumentException("Helper loader '$textual' is not ".($able?'callable.':'valid PHP callback.'));}$this->helperLoaders[]=$callback;}final
public
function
getHelpers(){return$this->helpers;}public
function
__call($name,$args){$lname=strtolower($name);if(!isset($this->helpers[$lname])){foreach($this->helperLoaders
as$loader){$helper=call_user_func($loader,$lname);if($helper){$this->registerHelper($lname,$helper);return
call_user_func_array($helper,$args);}}return
parent::__call($name,$args);}return
call_user_func_array($this->helpers[$lname],$args);}public
function
setTranslator(ITranslator$translator=NULL){$this->registerHelper('translate',$translator===NULL?NULL:array($translator,'translate'));return$this;}public
function
add($name,$value){if(array_key_exists($name,$this->params)){throw
new
InvalidStateException("The variable '$name' exists yet.");}$this->params[$name]=$value;}public
function
setParams(array$params){$this->params=$params;return$this;}public
function
getParams(){return$this->params;}public
function
__set($name,$value){$this->params[$name]=$value;}public
function&__get($name){if($this->warnOnUndefined&&!array_key_exists($name,$this->params)){trigger_error("The variable '$name' does not exist in template.",E_USER_NOTICE);}return$this->params[$name];}public
function
__isset($name){return
isset($this->params[$name]);}public
function
__unset($name){unset($this->params[$name]);}private
static
function
extractPhp($source,&$blocks){$res='';$blocks=array();foreach(token_get_all($source)as$token){if(is_array($token)){if($token[0]===T_INLINE_HTML){$res.=$token[1];unset($php);}else{if(!isset($php)){$res.=$php="\x01@php:p".count($blocks)."@\x02";$php=&$blocks[$php];}$php.=$token[1];}}else{$php.=$token;}}return$res;}public
static
function
optimizePhp($source){$res=$php='';$lastChar=';';$tokens=new
ArrayIterator(token_get_all($source));foreach($tokens
as$key=>$token){if(is_array($token)){if($token[0]===T_INLINE_HTML){$lastChar='';$res.=$token[1];}elseif($token[0]===T_CLOSE_TAG){$next=isset($tokens[$key+1])?$tokens[$key+1]:NULL;if(substr($res,-1)!=='<'&&preg_match('#^<\?php\s*$#',$php)){$php='';}elseif(is_array($next)&&$next[0]===T_OPEN_TAG){if($lastChar!==';'&&$lastChar!=='{'&&$lastChar!=='}'&&$lastChar!==':'&&$lastChar!=='/')$php.=$lastChar=';';if(substr($next[1],-1)==="\n")$php.="\n";$tokens->next();}elseif($next){$res.=preg_replace('#;?(\s)*$#','$1',$php).$token[1];$php='';}else{if($lastChar!=='}'&&$lastChar!==';')$php.=';';}}elseif($token[0]===T_ELSE||$token[0]===T_ELSEIF){if($tokens[$key+1]===':'&&$lastChar==='}')$php.=';';$lastChar='';$php.=$token[1];}else{if(!in_array($token[0],array(T_WHITESPACE,T_COMMENT,T_DOC_COMMENT,T_OPEN_TAG)))$lastChar='';$php.=$token[1];}}else{$php.=$lastChar=$token;}}return$res.$php;}}interface
IFileTemplate
extends
ITemplate{function
setFile($file);function
getFile();}class
Template
extends
BaseTemplate
implements
IFileTemplate{public
static$cacheExpire=FALSE;private
static$cacheStorage;private$file;public
function
__construct($file=NULL){if($file!==NULL){$this->setFile($file);}}public
function
setFile($file){if(!is_file($file)){throw
new
FileNotFoundException("Missing template file '$file'.");}$this->file=$file;return$this;}public
function
getFile(){return$this->file;}public
function
render(){if($this->file==NULL){throw
new
InvalidStateException("Template file name was not specified.");}$this->__set('template',$this);$cache=new
Cache($this->getCacheStorage(),'Nette.Template');$key=md5($this->file).'.'.basename($this->file);$cached=$content=$cache[$key];if($content===NULL){if(!$this->getFilters()){$this->onPrepareFilters($this);}if(!$this->getFilters()){LimitedScope::load($this->file,$this->getParams());return;}try{$shortName=$this->file;$shortName=str_replace(Environment::getVariable('appDir'),"\xE2\x80\xA6",$shortName);}catch(Exception$foo){}$content=$this->compile(file_get_contents($this->file),"file $shortName");$cache->save($key,$content,array(Cache::FILES=>$this->file,Cache::EXPIRE=>self::$cacheExpire));$cached=$cache[$key];}if($cached!==NULL&&self::$cacheStorage
instanceof
TemplateCacheStorage){LimitedScope::load($cached['file'],$this->getParams());fclose($cached['handle']);}else{LimitedScope::evaluate($content,$this->getParams());}}public
static
function
setCacheStorage(ICacheStorage$storage){self::$cacheStorage=$storage;}public
static
function
getCacheStorage(){if(self::$cacheStorage===NULL){self::$cacheStorage=new
TemplateCacheStorage(Environment::getVariable('tempDir'));}return
self::$cacheStorage;}}class
TemplateCacheStorage
extends
FileStorage{protected
function
readData($meta){return
array('file'=>$meta[self::FILE],'handle'=>$meta[self::HANDLE]);}protected
function
getCacheFile($key){return
parent::getCacheFile($key).'.php';}}class
CachingHelper
extends
Object{private$frame;private$key;public
static
function
create($key,$file,$tags){$cache=self::getCache();if(isset($cache[$key])){echo$cache[$key];return
FALSE;}else{$obj=new
self;$obj->key=$key;$obj->frame=array(Cache::FILES=>array($file),Cache::TAGS=>$tags,Cache::EXPIRE=>rand(86400*4,86400*7));ob_start();return$obj;}}public
function
save(){$this->getCache()->save($this->key,ob_get_flush(),$this->frame);$this->key=$this->frame=NULL;}public
function
addFile($file){$this->frame[Cache::FILES][]=$file;}public
function
addItem($item){$this->frame[Cache::ITEMS][]=$item;}protected
static
function
getCache(){return
Environment::getCache('Nette.Template.Curly');}}class
LatteFilter
extends
Object{const
RE_STRING='\'(?:\\\\.|[^\'\\\\])*\'|"(?:\\\\.|[^"\\\\])*"';const
RE_IDENTIFIER='[_a-zA-Z\x7F-\xFF][_a-zA-Z0-9\x7F-\xFF]*';const
HTML_PREFIX='n:';private$handler;private$macroRe;private$input,$output;private$offset;private$quote;private$tags;public$context,$escape;const
CONTEXT_TEXT='text';const
CONTEXT_CDATA='cdata';const
CONTEXT_TAG='tag';const
CONTEXT_ATTRIBUTE='attribute';const
CONTEXT_NONE='none';const
CONTEXT_COMMENT='comment';public
function
setHandler($handler){$this->handler=$handler;return$this;}public
function
getHandler(){if($this->handler===NULL){$this->handler=new
LatteMacros;}return$this->handler;}public
function
__invoke($s){if(!$this->macroRe){$this->setDelimiters('\\{(?![\\s\'"{}])','\\}');}$this->context=LatteFilter::CONTEXT_NONE;$this->escape='$template->escape';$this->getHandler()->initialize($this,$s);$s=$this->parse("\n".$s);$this->getHandler()->finalize($s);return$s;}private
function
parse($s){$this->input=&$s;$this->offset=0;$this->output='';$this->tags=array();$len=strlen($s);while($this->offset<$len){$matches=$this->{"context$this->context"}();if(!$matches){break;}elseif(!empty($matches['macro'])){preg_match('#^(/?[a-z]+)?(.*?)(\\|[a-z](?:'.self::RE_STRING.'|[^\'"\s]+)*)?$()#is',$matches['macro'],$m2);list(,$macro,$value,$modifiers)=$m2;$code=$this->handler->macro($macro,trim($value),isset($modifiers)?$modifiers:'');if($code===NULL){throw
new
InvalidStateException("Unknown macro {{$matches['macro']}} on line $this->line.");}$nl=isset($matches['newline'])?"\n":'';if($nl&&$matches['indent']&&strncmp($code,'<?php echo ',11)){$this->output.="\n".$code;}else{$this->output.=$matches['indent'].$code.(substr($code,-2)==='?>'?$nl:'');}}else{$this->output.=$matches[0];}}foreach($this->tags
as$tag){if(!$tag->isMacro&&!empty($tag->attrs)){throw
new
InvalidStateException("Missing end tag </$tag->name> for macro-attribute ".self::HTML_PREFIX.implode(' and '.self::HTML_PREFIX,array_keys($tag->attrs)).".");}}return$this->output.substr($this->input,$this->offset);}private
function
contextText(){$matches=$this->match('~
			(?:\n[ \t]*)?<(?P<closing>/?)(?P<tag>[a-z0-9:]+)|  ##  begin of HTML tag <tag </tag - ignores <!DOCTYPE
			<(?P<comment>!--)|           ##  begin of HTML comment <!--
			'.$this->macroRe.'           ##  curly tag
		~xsi');if(!$matches||!empty($matches['macro'])){}elseif(!empty($matches['comment'])){$this->context=self::CONTEXT_COMMENT;$this->escape='TemplateHelpers::escapeHtmlComment';}elseif(empty($matches['closing'])){$tag=$this->tags[]=(object)NULL;$tag->name=$matches['tag'];$tag->closing=FALSE;$tag->isMacro=String::startsWith($tag->name,self::HTML_PREFIX);$tag->attrs=array();$tag->pos=strlen($this->output);$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}else{do{$tag=array_pop($this->tags);if(!$tag){$tag=(object)NULL;$tag->name=$matches['tag'];$tag->isMacro=String::startsWith($tag->name,self::HTML_PREFIX);}}while(strcasecmp($tag->name,$matches['tag']));$this->tags[]=$tag;$tag->closing=TRUE;$tag->pos=strlen($this->output);$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextCData(){$tag=end($this->tags);$matches=$this->match('~
			</'.$tag->name.'(?![a-z0-9:])| ##  end HTML tag </tag
			'.$this->macroRe.'           ##  curly tag
		~xsi');if($matches&&empty($matches['macro'])){$tag->closing=TRUE;$tag->pos=strlen($this->output);$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextTag(){$matches=$this->match('~
			(?P<end>/?>)(?P<tagnewline>[\ \t]*(?=\r|\n))?|  ##  end of HTML tag
			'.$this->macroRe.'|          ##  curly tag
			\s*(?P<attr>[^\s/>={]+)(?:\s*=\s*(?P<value>["\']|[^\s/>{]+))? ## begin of HTML attribute
		~xsi');if(!$matches||!empty($matches['macro'])){}elseif(!empty($matches['end'])){$tag=end($this->tags);$isEmpty=!$tag->closing&&($matches['end'][0]==='/'||isset(Html::$emptyElements[strtolower($tag->name)]));if($tag->isMacro||!empty($tag->attrs)){if($tag->isMacro){$code=$this->handler->tagMacro(substr($tag->name,strlen(self::HTML_PREFIX)),$tag->attrs,$tag->closing);if($code===NULL){throw
new
InvalidStateException("Unknown tag-macro <$tag->name> on line $this->line.");}if($isEmpty){$code.=$this->handler->tagMacro(substr($tag->name,strlen(self::HTML_PREFIX)),$tag->attrs,TRUE);}}else{$code=substr($this->output,$tag->pos).$matches[0].(isset($matches['tagnewline'])?"\n":'');$code=$this->handler->attrsMacro($code,$tag->attrs,$tag->closing);if($code===NULL){throw
new
InvalidStateException("Unknown macro-attribute ".self::HTML_PREFIX.implode(' or '.self::HTML_PREFIX,array_keys($tag->attrs))." on line $this->line.");}if($isEmpty){$code=$this->handler->attrsMacro($code,$tag->attrs,TRUE);}}$this->output=substr_replace($this->output,$code,$tag->pos);$matches[0]='';}if($isEmpty){$tag->closing=TRUE;}if(!$tag->closing&&(strcasecmp($tag->name,'script')===0||strcasecmp($tag->name,'style')===0)){$this->context=self::CONTEXT_CDATA;$this->escape=strcasecmp($tag->name,'style')?'TemplateHelpers::escapeJs':'TemplateHelpers::escapeCss';}else{$this->context=self::CONTEXT_TEXT;$this->escape='TemplateHelpers::escapeHtml';if($tag->closing)array_pop($this->tags);}}else{$name=$matches['attr'];$value=empty($matches['value'])?TRUE:$matches['value'];if($isSpecial=String::startsWith($name,self::HTML_PREFIX)){$name=substr($name,strlen(self::HTML_PREFIX));}$tag=end($this->tags);if($isSpecial||$tag->isMacro){if($value==='"'||$value==="'"){if($matches=$this->match('~(.*?)'.$value.'~xsi')){$value=$matches[1];}}$tag->attrs[$name]=$value;$matches[0]='';}elseif($value==='"'||$value==="'"){$this->context=self::CONTEXT_ATTRIBUTE;$this->quote=$value;$this->escape=strncasecmp($name,'on',2)?(strcasecmp($name,'style')?'TemplateHelpers::escapeHtml':'TemplateHelpers::escapeHtmlCss'):'TemplateHelpers::escapeHtmlJs';}}return$matches;}private
function
contextAttribute(){$matches=$this->match('~
			('.$this->quote.')|      ##  1) end of HTML attribute
			'.$this->macroRe.'           ##  curly tag
		~xsi');if($matches&&empty($matches['macro'])){$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextComment(){$matches=$this->match('~
			(--\s*>)|                    ##  1) end of HTML comment
			'.$this->macroRe.'           ##  curly tag
		~xsi');if($matches&&empty($matches['macro'])){$this->context=self::CONTEXT_TEXT;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextNone(){$matches=$this->match('~
			'.$this->macroRe.'           ##  curly tag
		~xsi');return$matches;}private
function
match($re){if(preg_match($re,$this->input,$matches,PREG_OFFSET_CAPTURE,$this->offset)){$this->output.=substr($this->input,$this->offset,$matches[0][1]-$this->offset);$this->offset=$matches[0][1]+strlen($matches[0][0]);foreach($matches
as$k=>$v)$matches[$k]=$v[0];}return$matches;}public
function
getLine(){return
substr_count($this->input,"\n",0,$this->offset);}public
function
setDelimiters($left,$right){$this->macroRe='
			(?P<indent>\n[\ \t]*)?
			'.$left.'
				(?P<macro>(?:'.self::RE_STRING.'|[^\'"]+?)*?)
			'.$right.'
			(?P<newline>[\ \t]*(?=\r|\n))?
		';return$this;}public
static
function
formatModifiers($var,$modifiers){if(!$modifiers)return$var;preg_match_all('~
				'.self::RE_STRING.'|  ## single or double quoted string
				[^\'"|:,]+|           ## symbol
				[|:,]                 ## separator
			~xs',$modifiers.'|',$tokens);$inside=FALSE;$prev='';foreach($tokens[0]as$token){if($token==='|'||$token===':'||$token===','){if($prev===''){}elseif(!$inside){if(!preg_match('#^'.self::RE_IDENTIFIER.'$#',$prev)){throw
new
InvalidStateException("Modifier name must be alphanumeric string, '$prev' given.");}$var="\$template->$prev($var";$prev='';$inside=TRUE;}else{$var.=', '.self::formatString($prev);$prev='';}if($token==='|'&&$inside){$var.=')';$inside=FALSE;}}else{$prev.=$token;}}return$var;}public
static
function
fetchToken(&$s){if(preg_match('#^((?>'.self::RE_STRING.'|[^\'"\s,]+)+)\s*,?\s*(.*)$#',$s,$matches)){$s=$matches[2];return$matches[1];}return
NULL;}public
static
function
formatArray($s,$prefix=''){$s=preg_replace_callback('~
				'.self::RE_STRING.'|                          ## single or double quoted string
				(?<=[,=(]|=>|^)\s*([a-z\d_]+)(?=\s*[,=)]|$)   ## 1) symbol
			~xi',array(__CLASS__,'cbArgs'),trim($s));return$s===''?'':$prefix."array($s)";}private
static
function
cbArgs($matches){if(!empty($matches[1])){list(,$symbol)=$matches;static$keywords=array('true'=>1,'false'=>1,'null'=>1,'and'=>1,'or'=>1,'xor'=>1,'clone'=>1,'new'=>1);return
is_numeric($symbol)||isset($keywords[strtolower($symbol)])?$matches[0]:"'$symbol'";}else{return$matches[0];}}public
static
function
formatString($s){return(is_numeric($s)||strspn($s,'\'"$'))?$s:'"'.$s.'"';}public
static
function
invoke($s){$filter=new
self;return$filter->__invoke($s);}}class
CurlyBracketsFilter
extends
LatteFilter{}class
CurlyBracketsMacros
extends
LatteMacros{}class
LatteMacros
extends
Object{public
static$defaultMacros=array('syntax'=>'%:macroSyntax%','/syntax'=>'%:macroSyntax%','block'=>'<?php %:macroBlock% ?>','/block'=>'<?php %:macroBlockEnd% ?>','capture'=>'<?php %:macroCapture% ?>','/capture'=>'<?php %:macroCaptureEnd% ?>','snippet'=>'<?php %:macroSnippet% ?>','/snippet'=>'<?php %:macroSnippetEnd% ?>','cache'=>'<?php if ($_cb->foo = CachingHelper::create($_cb->key = md5(__FILE__) . __LINE__, $template->getFile(), array(%%))) { $_cb->caches[] = $_cb->foo ?>','/cache'=>'<?php array_pop($_cb->caches)->save(); } if (!empty($_cb->caches)) end($_cb->caches)->addItem($_cb->key) ?>','if'=>'<?php if (%%): ?>','elseif'=>'<?php elseif (%%): ?>','else'=>'<?php else: ?>','/if'=>'<?php endif ?>','ifset'=>'<?php if (isset(%%)): ?>','/ifset'=>'<?php endif ?>','elseifset'=>'<?php elseif (isset(%%)): ?>','foreach'=>'<?php foreach (%:macroForeach%): ?>','/foreach'=>'<?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>','for'=>'<?php for (%%): ?>','/for'=>'<?php endfor ?>','while'=>'<?php while (%%): ?>','/while'=>'<?php endwhile ?>','continueIf'=>'<?php if (%%) continue ?>','breakIf'=>'<?php if (%%) break ?>','include'=>'<?php %:macroInclude% ?>','extends'=>'<?php %:macroExtends% ?>','layout'=>'<?php %:macroExtends% ?>','plink'=>'<?php echo %:macroEscape%(%:macroPlink%) ?>','link'=>'<?php echo %:macroEscape%(%:macroLink%) ?>','ifCurrent'=>'<?php %:macroIfCurrent%; if ($presenter->getLastCreatedRequestFlag("current")): ?>','widget'=>'<?php %:macroWidget% ?>','control'=>'<?php %:macroWidget% ?>','attr'=>'<?php echo Html::el(NULL)->%:macroAttr%attributes() ?>','contentType'=>'<?php %:macroContentType% ?>','status'=>'<?php Environment::getHttpResponse()->setCode(%%) ?>','assign'=>'<?php %:macroAssign% ?>','default'=>'<?php %:macroDefault% ?>','dump'=>'<?php Debug::consoleDump(%:macroDump%, "Template " . str_replace(Environment::getVariable("appDir"), "\xE2\x80\xA6", $template->getFile())) ?>','debugbreak'=>'<?php if (function_exists("debugbreak")) debugbreak() ?>','!_'=>'<?php echo %:macroTranslate% ?>','!='=>'<?php echo %:macroModifiers% ?>','_'=>'<?php echo %:macroEscape%(%:macroTranslate%) ?>','='=>'<?php echo %:macroEscape%(%:macroModifiers%) ?>','!$'=>'<?php echo %:macroVar% ?>','!'=>'<?php echo %:macroVar% ?>','$'=>'<?php echo %:macroEscape%(%:macroVar%) ?>','?'=>'<?php %:macroModifiers% ?>');public$macros;private$filter;private$current;private$blocks=array();private$namedBlocks=array();private$extends;private$uniq;const
BLOCK_NAMED=1;const
BLOCK_CAPTURE=2;const
BLOCK_ANONYMOUS=3;public
function
__construct(){$this->macros=self::$defaultMacros;}public
function
initialize($filter,&$s){$this->filter=$filter;$this->blocks=array();$this->namedBlocks=array();$this->extends=NULL;$this->uniq=substr(md5(uniqid()),0,10);$filter->context=LatteFilter::CONTEXT_TEXT;$filter->escape='TemplateHelpers::escapeHtml';$s=preg_replace('#\\{\\*.*?\\*\\}[\r\n]*#s','',$s);$s=preg_replace('#@(\\{[^}]+?\\})#s','<?php } ?>$1<?php if (SnippetHelper::\\$outputAllowed) { ?>',$s);}public
function
finalize(&$s){if(count($this->blocks)===1){$s.=$this->macro('/block','','');}elseif($this->blocks){throw
new
InvalidStateException("There are some unclosed blocks.");}$s="<?php\nif (SnippetHelper::\$outputAllowed) {\n?>$s<?php\n}\n?>";if($this->namedBlocks||$this->extends){$s="<?php\n".'if ($_cb->extends) { ob_start(); }'."\n".'?>'.$s."<?php\n".'if ($_cb->extends) { ob_end_clean(); LatteMacros::includeTemplate($_cb->extends, get_defined_vars(), $template)->render(); }'."\n";}if($this->namedBlocks){foreach(array_reverse($this->namedBlocks,TRUE)as$name=>$foo){$name=preg_quote($name,'#');$s=preg_replace_callback("#{block ($name)} \?>(.*)<\?php {/block $name}#sU",array($this,'cbNamedBlocks'),$s);}$s="<?php\n\n".implode("\n\n\n",$this->namedBlocks)."\n\n//\n// end of blocks\n//\n?>".$s;}$s="<?php\n"."\$_cb = LatteMacros::initRuntime(\$template, ".var_export($this->extends,TRUE).", ".var_export($this->uniq,TRUE)."); unset(\$_extends);\n".'?>'.$s;}public
function
macro($macro,$content,$modifiers){if($macro===''){$macro=substr($content,0,2);if(!isset($this->macros[$macro])){$macro=substr($content,0,1);if(!isset($this->macros[$macro])){return
NULL;}}$content=substr($content,strlen($macro));}elseif(!isset($this->macros[$macro])){return
NULL;}$this->current=array($content,$modifiers);return
preg_replace_callback('#%(.*?)%#',array($this,'cbMacro'),$this->macros[$macro]);}private
function
cbMacro($m){list($content,$modifiers)=$this->current;if($m[1]){$callback=$m[1][0]===':'?array($this,substr($m[1],1)):$m[1];fixCallback($callback);if(!is_callable($callback)){$able=is_callable($callback,TRUE,$textual);throw
new
InvalidStateException("Latte macro handler '$textual' is not ".($able?'callable.':'valid PHP callback.'));}return
call_user_func($callback,$content,$modifiers);}else{return$content;}}public
function
tagMacro($name,$attrs,$closing){$knownTags=array('include'=>'block','for'=>'each','block'=>'name','if'=>'cond','elseif'=>'cond');return$this->macro($closing?"/$name":$name,isset($knownTags[$name],$attrs[$knownTags[$name]])?$attrs[$knownTags[$name]]:substr(var_export($attrs,TRUE),8,-1),isset($attrs['modifiers'])?$attrs['modifiers']:'');}public
function
attrsMacro($code,$attrs,$closing){$left=$right='';foreach($this->macros
as$name=>$foo){if(!isset($this->macros["/$name"])){continue;}$macro=$closing?"/$name":$name;if(isset($attrs[$name])){if($closing){$right.=$this->macro($macro,'','');}else{$left=$this->macro($macro,$attrs[$name],'').$left;}}$innerName="inner-$name";if(isset($attrs[$innerName])){if($closing){$left.=$this->macro($macro,'','');}else{$right=$this->macro($macro,$attrs[$innerName],'').$right;}}$tagName="tag-$name";if(isset($attrs[$tagName])){$left=$this->macro($name,$attrs[$tagName],'').$left;$right.=$this->macro("/$name",'','');}unset($attrs[$name],$attrs[$innerName],$attrs[$tagName]);}return$attrs?NULL:$left.$code.$right;}private
function
macroVar($var,$modifiers){return
LatteFilter::formatModifiers('$'.$var,$modifiers);}private
function
macroTranslate($var,$modifiers){return
LatteFilter::formatModifiers($var,'translate|'.$modifiers);}private
function
macroSyntax($var){switch($var){case'':case'latte':$this->filter->setDelimiters('\\{(?![\\s\'"{}])','\\}');break;case'double':$this->filter->setDelimiters('\\{\\{(?![\\s\'"{}])','\\}\\}');break;case'asp':$this->filter->setDelimiters('<%\s*','\s*%>');break;case'python':$this->filter->setDelimiters('\\{[{%]\s*','\s*[%}]\\}');break;case'off':$this->filter->setDelimiters('[^\x00-\xFF]','');break;default:throw
new
InvalidStateException("Unknown macro syntax '$var' on line {$this->filter->line}.");}}private
function
macroInclude($content,$modifiers){$destination=LatteFilter::fetchToken($content);$params=LatteFilter::formatArray($content).($content?' + ':'');if($destination===NULL){throw
new
InvalidStateException("Missing destination in {include} on line {$this->filter->line}.");}elseif($destination[0]==='#'){$destination=ltrim($destination,'#');if(!preg_match('#^'.LatteFilter::RE_IDENTIFIER.'$#',$destination)){throw
new
InvalidStateException("Included block name must be alphanumeric string, '$destination' given on line {$this->filter->line}.");}$parent=$destination==='parent';if($destination==='parent'||$destination==='this'){$item=end($this->blocks);while($item&&$item[0]!==self::BLOCK_NAMED)$item=prev($this->blocks);if(!$item){throw
new
InvalidStateException("Cannot include $destination block outside of any block on line {$this->filter->line}.");}$destination=$item[1];}$name=var_export($destination,TRUE);$params.='get_defined_vars()';$cmd=isset($this->namedBlocks[$destination])&&!$parent?"call_user_func(reset(\$_cb->blocks[$name]), $params)":"LatteMacros::callBlock".($parent?'Parent':'')."(\$_cb->blocks, $name, $params)";return$modifiers?"ob_start(); $cmd; echo ".LatteFilter::formatModifiers('ob_get_clean()',$modifiers):$cmd;}else{$destination=LatteFilter::formatString($destination);$params.='$template->getParams()';return$modifiers?'echo '.LatteFilter::formatModifiers('LatteMacros::includeTemplate('.$destination.', '.$params.', $_cb->templates['.var_export($this->uniq,TRUE).'])->__toString(TRUE)',$modifiers):'LatteMacros::includeTemplate('.$destination.', '.$params.', $_cb->templates['.var_export($this->uniq,TRUE).'])->render()';}}private
function
macroExtends($content){$destination=LatteFilter::fetchToken($content);if($destination===NULL){throw
new
InvalidStateException("Missing destination in {extends} on line {$this->filter->line}.");}if(!empty($this->blocks)){throw
new
InvalidStateException("{extends} must be placed outside any block; on line {$this->filter->line}.");}if($this->extends!==NULL){throw
new
InvalidStateException("Multiple {extends} declarations are not allowed; on line {$this->filter->line}.");}$this->extends=$destination!=='none';return$this->extends?'$_cb->extends = '.LatteFilter::formatString($destination):'';}private
function
macroBlock($content,$modifiers){if(substr($content,0,1)==='$'){trigger_error("Capturing {block $content} is deprecated; use {capture $content} instead on line {$this->filter->line}.",E_USER_WARNING);return$this->macroCapture($content,$modifiers);}$name=LatteFilter::fetchToken($content);if($name===NULL){$this->blocks[]=array(self::BLOCK_ANONYMOUS,NULL,$modifiers);return$modifiers===''?'':'ob_start()';}else{$name=ltrim($name,'#');if(!preg_match('#^'.LatteFilter::RE_IDENTIFIER.'$#',$name)){throw
new
InvalidStateException("Block name must be alphanumeric string, '$name' given on line {$this->filter->line}.");}elseif(isset($this->namedBlocks[$name])){throw
new
InvalidStateException("Cannot redeclare block '$name'; on line {$this->filter->line}.");}$top=empty($this->blocks);$this->namedBlocks[$name]=$name;$this->blocks[]=array(self::BLOCK_NAMED,$name,'');if(!$top){return$this->macroInclude('#'.$name,$modifiers)."{block $name}";}elseif($this->extends){return"{block $name}";}else{return'if (!$_cb->extends) { '.$this->macroInclude('#'.$name,$modifiers)."; } {block $name}";}}}private
function
macroBlockEnd($content){list($type,$name,$modifiers)=array_pop($this->blocks);if($type===self::BLOCK_CAPTURE){$this->blocks[]=array($type,$name,$modifiers);return$this->macroCaptureEnd($content);}if(($type!==self::BLOCK_NAMED&&$type!==self::BLOCK_ANONYMOUS)||($content&&$content!==$name)){throw
new
InvalidStateException("Tag {/block $content} was not expected here on line {$this->filter->line}.");}elseif($type===self::BLOCK_NAMED){return"{/block $name}";}else{return$modifiers===''?'':'echo '.LatteFilter::formatModifiers('ob_get_clean()',$modifiers);}}private
function
macroSnippet($content){$args=array('');if($snippet=LatteFilter::fetchToken($content)){$args[]=LatteFilter::formatString($snippet);}if($content){$args[]=LatteFilter::formatString($content);}return'} if ($_cb->foo = SnippetHelper::create($control'.implode(', ',$args).')) { $_cb->snippets[] = $_cb->foo';}private
function
macroSnippetEnd($content){return'array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) {';}private
function
macroCapture($content,$modifiers){$name=LatteFilter::fetchToken($content);if(substr($name,0,1)!=='$'){throw
new
InvalidStateException("Invalid capture block parameter '$name' on line {$this->filter->line}.");}$this->blocks[]=array(self::BLOCK_CAPTURE,$name,$modifiers);return'ob_start()';}private
function
macroCaptureEnd($content){list($type,$name,$modifiers)=array_pop($this->blocks);if($type!==self::BLOCK_CAPTURE||($content&&$content!==$name)){throw
new
InvalidStateException("Tag {/capture $content} was not expected here on line {$this->filter->line}.");}return$name.'='.LatteFilter::formatModifiers('ob_get_clean()',$modifiers);}private
function
cbNamedBlocks($matches){list(,$name,$content)=$matches;$func='_cbb'.substr(md5($this->uniq.$name),0,10).'_'.preg_replace('#[^a-z0-9_]#i','_',$name);$this->namedBlocks[$name]="//\n// block $name\n//\n"."if (!function_exists(\$_cb->blocks[".var_export($name,TRUE)."][] = '$func')) { function $func() { extract(func_get_arg(0))\n?>$content<?php\n}}";return'';}private
function
macroForeach($content){return'$iterator = $_cb->its[] = new SmartCachingIterator('.preg_replace('# +as +#i',') as ',$content,1);}private
function
macroAttr($content){return
preg_replace('#\)\s+#',')->',$content.' ');}private
function
macroContentType($content){if(strpos($content,'html')!==FALSE){$this->filter->escape='TemplateHelpers::escapeHtml';$this->filter->context=LatteFilter::CONTEXT_TEXT;}elseif(strpos($content,'xml')!==FALSE){$this->filter->escape='TemplateHelpers::escapeXml';$this->filter->context=LatteFilter::CONTEXT_NONE;}elseif(strpos($content,'javascript')!==FALSE){$this->filter->escape='TemplateHelpers::escapeJs';$this->filter->context=LatteFilter::CONTEXT_NONE;}elseif(strpos($content,'css')!==FALSE){$this->filter->escape='TemplateHelpers::escapeCss';$this->filter->context=LatteFilter::CONTEXT_NONE;}elseif(strpos($content,'plain')!==FALSE){$this->filter->escape='';$this->filter->context=LatteFilter::CONTEXT_NONE;}else{$this->filter->escape='$template->escape';$this->filter->context=LatteFilter::CONTEXT_NONE;}return
strpos($content,'/')?'Environment::getHttpResponse()->setHeader("Content-Type", "'.$content.'")':'';}private
function
macroDump($content){return$content?"array('$content' => $content)":'get_defined_vars()';}private
function
macroWidget($content){$pair=LatteFilter::fetchToken($content);if($pair===NULL){throw
new
InvalidStateException("Missing widget name in {widget} on line {$this->filter->line}.");}$pair=explode(':',$pair,2);$widget=LatteFilter::formatString($pair[0]);$method=isset($pair[1])?ucfirst($pair[1]):'';$method=preg_match('#^('.LatteFilter::RE_IDENTIFIER.'|)$#',$method)?"render$method":"{\"render$method\"}";$param=LatteFilter::formatArray($content);if(strpos($content,'=>')===FALSE)$param=substr($param,6,-1);return($widget[0]==='$'?"if (is_object($widget)) {$widget}->$method($param); else ":'')."\$control->getWidget($widget)->$method($param)";}private
function
macroLink($content,$modifiers){return
LatteFilter::formatModifiers('$control->link('.$this->formatLink($content).')',$modifiers);}private
function
macroPlink($content,$modifiers){return
LatteFilter::formatModifiers('$presenter->link('.$this->formatLink($content).')',$modifiers);}private
function
macroIfCurrent($content){return$content?'try { $presenter->link('.$this->formatLink($content).'); } catch (InvalidLinkException $e) {}':'';}private
function
formatLink($content){return
LatteFilter::formatString(LatteFilter::fetchToken($content)).LatteFilter::formatArray($content,', ');}private
function
macroAssign($content,$modifiers){if(!$content){throw
new
InvalidStateException("Missing arguments in {assign} on line {$this->filter->line}.");}if(strpos($content,'=>')===FALSE){return'$'.ltrim(LatteFilter::fetchToken($content),'$').' = '.LatteFilter::formatModifiers($content===''?'NULL':$content,$modifiers);}return'extract('.LatteFilter::formatArray($content).')';}private
function
macroDefault($content){if(!$content){throw
new
InvalidStateException("Missing arguments in {default} on line {$this->filter->line}.");}return'extract('.LatteFilter::formatArray($content).', EXTR_SKIP)';}private
function
macroEscape($content){return$this->filter->escape;}private
function
macroModifiers($content,$modifiers){return
LatteFilter::formatModifiers($content,$modifiers);}public
static
function
callBlock(&$blocks,$name,$params){if(empty($blocks[$name])){throw
new
InvalidStateException("Call to undefined block '$name'.");}$block=reset($blocks[$name]);$block($params);}public
static
function
callBlockParent(&$blocks,$name,$params){if(empty($blocks[$name])||($block=next($blocks[$name]))===FALSE){throw
new
InvalidStateException("Call to undefined parent block '$name'.");}$block($params);}public
static
function
includeTemplate($destination,$params,$template){if($destination
instanceof
ITemplate){$tpl=$destination;}elseif($destination==NULL){throw
new
InvalidArgumentException("Template file name was not specified.");}else{$tpl=clone$template;if($template
instanceof
IFileTemplate){if(substr($destination,0,1)!=='/'&&substr($destination,1,1)!==':'){$destination=dirname($template->getFile()).'/'.$destination;}$tpl->setFile($destination);}}$tpl->setParams($params);return$tpl;}public
static
function
initRuntime($template,$extends,$realFile){$cb=(object)NULL;if(isset($template->_cb)){$cb->blocks=&$template->_cb->blocks;$cb->templates=&$template->_cb->templates;}$cb->templates[$realFile]=$template;$cb->extends=is_bool($extends)?$extends:(empty($template->_extends)?FALSE:$template->_extends);unset($template->_cb,$template->_extends);if(!empty($cb->caches)){end($cb->caches)->addFile($template->getFile());}return$cb;}}class
SnippetHelper
extends
Object{public
static$outputAllowed=TRUE;private$id;private$tag;private$payload;private$level;public
static
function
create(Control$control,$name=NULL,$tag='div'){if(self::$outputAllowed){$obj=new
self;$obj->tag=trim($tag,'<>');if($obj->tag)echo'<',$obj->tag,' id="',$control->getSnippetId($name),'">';return$obj;}elseif($control->isControlInvalid($name)){$obj=new
self;$obj->id=$control->getSnippetId($name);$obj->payload=$control->getPresenter()->getPayload();ob_start();$obj->level=ob_get_level();self::$outputAllowed=TRUE;return$obj;}else{return
FALSE;}}public
function
finish(){if($this->tag!==NULL){if($this->tag)echo"</$this->tag>";}else{if($this->level!==ob_get_level()){throw
new
InvalidStateException("Snippet '$this->id' cannot be ended here.");}$this->payload->snippets[$this->id]=ob_get_clean();self::$outputAllowed=FALSE;}}}final
class
TemplateFilters{final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
removePhp($s){return
preg_replace('#\x01@php:p\d+@\x02#','<?php ?>',$s);}public
static
function
relativeLinks($s){return
preg_replace('#(src|href|action)\s*=\s*(["\'])(?![a-z]+:|[\x01/\\#])#','$1=$2<?php echo \\$baseUri ?>',$s);}public
static
function
netteLinks($s){return
preg_replace_callback('#(src|href|action)\s*=\s*(["\'])(nette:.*?)([\#"\'])#',array(__CLASS__,'netteLinksCb'),$s);}private
static
function
netteLinksCb($m){list(,$attr,$quote,$uri,$fragment)=$m;$parts=parse_url($uri);if(isset($parts['scheme'])&&$parts['scheme']==='nette'){return$attr.'='.$quote.'<?php echo $template->escape($control->'."link('".(isset($parts['path'])?$parts['path']:'this!').(isset($parts['query'])?'?'.$parts['query']:'').'\'))?>'.$fragment;}else{return$m[0];}}public
static$texy;public
static
function
texyElements($s){return
preg_replace_callback('#<texy([^>]*)>(.*?)</texy>#s',array(__CLASS__,'texyCb'),$s);}private
static
function
texyCb($m){list(,$mAttrs,$mContent)=$m;$attrs=array();if($mAttrs){preg_match_all('#([a-z0-9:-]+)\s*(?:=\s*(\'[^\']*\'|"[^"]*"|[^\'"\s]+))?()#isu',$mAttrs,$arr,PREG_SET_ORDER);foreach($arr
as$m){$key=strtolower($m[1]);$val=$m[2];if($val==NULL)$attrs[$key]=TRUE;elseif($val{0}==='\''||$val{0}==='"')$attrs[$key]=html_entity_decode(substr($val,1,-1),ENT_QUOTES,'UTF-8');else$attrs[$key]=html_entity_decode($val,ENT_QUOTES,'UTF-8');}}return
self::$texy->process($m[2]);}}final
class
TemplateHelpers{final
public
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}public
static
function
loader($helper){$callback='Nette\Templates\TemplateHelpers::'.$helper;fixCallback($callback);if(is_callable($callback)){return$callback;}$callback='Nette\String::'.$helper;fixCallback($callback);if(is_callable($callback)){return$callback;}}public
static
function
escapeHtml($s){if(is_object($s)&&($s
instanceof
ITemplate||$s
instanceof
Html||$s
instanceof
Form)){return$s->__toString(TRUE);}return
htmlSpecialChars($s,ENT_QUOTES);}public
static
function
escapeHtmlComment($s){return
str_replace('--','--><!--',$s);}public
static
function
escapeXML($s){return
htmlSpecialChars(preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]+#','',$s),ENT_QUOTES);}public
static
function
escapeCss($s){return
addcslashes($s,"\x00..\x2C./:;<=>?@[\\]^`{|}~");}public
static
function
escapeHtmlCss($s){return
htmlSpecialChars(self::escapeCss($s),ENT_QUOTES);}public
static
function
escapeJs($s){if(is_object($s)&&($s
instanceof
ITemplate||$s
instanceof
Html||$s
instanceof
Form)){$s=$s->__toString(TRUE);}return
str_replace(']]>',']]\x3E',json_encode($s));}public
static
function
escapeHtmlJs($s){return
htmlSpecialChars(self::escapeJs($s),ENT_QUOTES);}public
static
function
strip($s){$s=preg_replace_callback('#<(textarea|pre|script).*?</\\1#si',array(__CLASS__,'indentCb'),$s);$s=trim(preg_replace('#[ \t\r\n]+#',' ',$s));return
strtr($s,"\x1F\x1E\x1D\x1A"," \t\r\n");}public
static
function
indent($s,$level=1,$chars="\t"){if($level>=1){$s=preg_replace_callback('#<(textarea|pre).*?</\\1#si',array(__CLASS__,'indentCb'),$s);$s=String::indent($s,$level,$chars);$s=strtr($s,"\x1F\x1E\x1D\x1A"," \t\r\n");}return$s;}private
static
function
indentCb($m){return
strtr($m[0]," \t\r\n","\x1F\x1E\x1D\x1A");}public
static
function
date($time,$format="%x"){if($time==NULL){return
NULL;}elseif(!($time
instanceof
DateTime)){$time=new
DateTime(is_numeric($time)?date('Y-m-d H:i:s',$time):$time);}return
strpos($format,'%')===FALSE?$time->format($format):strftime($format,$time->format('U'));}public
static
function
bytes($bytes,$precision=2){$bytes=round($bytes);$units=array('B','kB','MB','GB','TB','PB');foreach($units
as$unit){if(abs($bytes)<1024||$unit===end($units))break;$bytes=$bytes/1024;}return
round($bytes,$precision).' '.$unit;}public
static
function
length($var){return
is_string($var)?iconv_strlen($var,'UTF-8'):count($var);}public
static
function
replace($subject,$search,$replacement=''){return
str_replace($search,$replacement,$subject);}public
static
function
replaceRe($subject,$pattern,$replacement=''){return
preg_replace($pattern,$replacement,$subject);}public
static
function
null($value){return'';}}class
Ftp
extends
Object{const
ASCII=FTP_ASCII;const
TEXT=FTP_TEXT;const
BINARY=FTP_BINARY;const
IMAGE=FTP_IMAGE;const
TIMEOUT_SEC=FTP_TIMEOUT_SEC;const
AUTOSEEK=FTP_AUTOSEEK;const
AUTORESUME=FTP_AUTORESUME;const
FAILED=FTP_FAILED;const
FINISHED=FTP_FINISHED;const
MOREDATA=FTP_MOREDATA;private$resource;private$state;public
function
__construct(){if(!extension_loaded('ftp')){throw
new
Exception("PHP extension FTP is not loaded.");}}public
function
__call($name,$args){$name=strtolower($name);$silent=strncmp($name,'try',3)===0;$func=$silent?substr($name,3):$name;static$aliases=array('sslconnect'=>'ssl_connect','getoption'=>'get_option','setoption'=>'set_option','nbcontinue'=>'nb_continue','nbfget'=>'nb_fget','nbfput'=>'nb_fput','nbget'=>'nb_get','nbput'=>'nb_put');$func='ftp_'.(isset($aliases[$func])?$aliases[$func]:$func);if(!function_exists($func)){return
parent::__call($name,$args);}Tools::tryError();if($func==='ftp_connect'||$func==='ftp_ssl_connect'){$this->state=array($name=>$args);$this->resource=call_user_func_array($func,$args);$res=NULL;}elseif(!is_resource($this->resource)){Tools::catchError($msg);throw
new
FtpException("Not connected to FTP server. Call connect() or ssl_connect() first.");}else{if($func==='ftp_login'||$func==='ftp_pasv'){$this->state[$name]=$args;}array_unshift($args,$this->resource);$res=call_user_func_array($func,$args);if($func==='ftp_chdir'||$func==='ftp_cdup'){$this->state['chdir']=array(ftp_pwd($this->resource));}}if(Tools::catchError($msg)&&!$silent){throw
new
FtpException($msg);}return$res;}public
function
reconnect(){@ftp_close($this->resource);foreach($this->state
as$name=>$args){call_user_func_array(array($this,$name),$args);}}public
function
fileExists($file){return
is_array($this->nlist($file));}public
function
isDir($dir){$current=$this->pwd();try{$this->chdir($dir);}catch(FtpException$e){}$this->chdir($current);return
empty($e);}public
function
mkDirRecursive($dir){$parts=explode('/',$dir);$path='';while(!empty($parts)){$path.=array_shift($parts);try{if($path!=='')$this->mkdir($path);}catch(FtpException$e){if(!$this->isDir($path)){throw
new
FtpException("Cannot create directory '$path'.");}}$path.='/';}}}class
FtpException
extends
Exception{}class
Html
extends
Object
implements
ArrayAccess,Countable,IteratorAggregate{private$name;private$isEmpty;public$attrs=array();protected$children=array();public
static$xhtml=TRUE;public
static$emptyElements=array('img'=>1,'hr'=>1,'br'=>1,'input'=>1,'meta'=>1,'area'=>1,'command'=>1,'keygen'=>1,'source'=>1,'base'=>1,'col'=>1,'link'=>1,'param'=>1,'basefont'=>1,'frame'=>1,'isindex'=>1,'wbr'=>1,'embed'=>1);public
static
function
el($name=NULL,$attrs=NULL){$el=new
self;$parts=explode(' ',$name,2);$el->setName($parts[0]);if(is_array($attrs)){$el->attrs=$attrs;}elseif($attrs!==NULL){$el->setText($attrs);}if(isset($parts[1])){preg_match_all('#([a-z0-9:-]+)(?:=(["\'])?(.*?)(?(2)\\2|\s))?#i',$parts[1].' ',$parts,PREG_SET_ORDER);foreach($parts
as$m){$el->attrs[$m[1]]=isset($m[3])?$m[3]:TRUE;}}return$el;}final
public
function
setName($name,$isEmpty=NULL){if($name!==NULL&&!is_string($name)){throw
new
InvalidArgumentException("Name must be string or NULL, ".gettype($name)." given.");}$this->name=$name;$this->isEmpty=$isEmpty===NULL?isset(self::$emptyElements[$name]):(bool)$isEmpty;return$this;}final
public
function
getName(){return$this->name;}final
public
function
isEmpty(){return$this->isEmpty;}final
public
function
__set($name,$value){$this->attrs[$name]=$value;}final
public
function&__get($name){return$this->attrs[$name];}final
public
function
__unset($name){unset($this->attrs[$name]);}final
public
function
__call($m,$args){$p=substr($m,0,3);if($p==='get'||$p==='set'||$p==='add'){$m=substr($m,3);$m[0]=$m[0]|"\x20";if($p==='get'){return
isset($this->attrs[$m])?$this->attrs[$m]:NULL;}elseif($p==='add'){$args[]=TRUE;}}if(count($args)===1){$this->attrs[$m]=$args[0];}elseif($args[0]==NULL){$tmp=&$this->attrs[$m];}elseif(!isset($this->attrs[$m])||is_array($this->attrs[$m])){$this->attrs[$m][$args[0]]=$args[1];}else{$this->attrs[$m]=array($this->attrs[$m],$args[0]=>$args[1]);}return$this;}final
public
function
href($path,$query=NULL){if($query){$query=http_build_query($query,NULL,'&');if($query!=='')$path.='?'.$query;}$this->attrs['href']=$path;return$this;}final
public
function
setHtml($html){if($html===NULL){$html='';}elseif(is_array($html)){throw
new
InvalidArgumentException("Textual content must be a scalar, ".gettype($html)." given.");}else{$html=(string)$html;}$this->removeChildren();$this->children[]=$html;return$this;}final
public
function
getHtml(){$s='';foreach($this->children
as$child){if(is_object($child))return
FALSE;$s.=$child;}return$s;}final
public
function
setText($text){if(!is_array($text)){$text=str_replace(array('&','<','>'),array('&amp;','&lt;','&gt;'),(string)$text);}return$this->setHtml($text);}final
public
function
getText(){return$this->getHtml();}final
public
function
add($child){return$this->insert(NULL,$child);}final
public
function
create($name,$attrs=NULL){$this->insert(NULL,$child=self::el($name,$attrs));return$child;}public
function
insert($index,$child,$replace=FALSE){if($child
instanceof
Html||is_scalar($child)){if($index===NULL){$this->children[]=$child;}else{array_splice($this->children,(int)$index,$replace?1:0,array($child));}}else{throw
new
InvalidArgumentException("Child node must be scalar or Html object, ".(is_object($child)?get_class($child):gettype($child))." given.");}return$this;}final
public
function
offsetSet($index,$child){$this->insert($index,$child,TRUE);}final
public
function
offsetGet($index){return$this->children[$index];}final
public
function
offsetExists($index){return
isset($this->children[$index]);}public
function
offsetUnset($index){if(isset($this->children[$index])){array_splice($this->children,(int)$index,1);}}final
public
function
count(){return
count($this->children);}public
function
removeChildren(){$this->children=array();}final
public
function
getIterator($deep=FALSE){if($deep){$deep=$deep>0?RecursiveIteratorIterator::SELF_FIRST:RecursiveIteratorIterator::CHILD_FIRST;return
new
RecursiveIteratorIterator(new
RecursiveHtmlIterator($this->children),$deep);}else{return
new
RecursiveHtmlIterator($this->children);}}final
public
function
getChildren(){return$this->children;}final
public
function
render($indent=NULL){$s=$this->startTag();if($this->isEmpty){return$s;}if($indent!==NULL){$indent++;}foreach($this->children
as$child){if(is_object($child)){$s.=$child->render($indent);}else{$s.=$child;}}$s.=$this->endTag();if($indent!==NULL){return"\n".str_repeat("\t",$indent-1).$s."\n".str_repeat("\t",max(0,$indent-2));}return$s;}final
public
function
__toString(){return$this->render();}final
public
function
startTag(){if($this->name){return'<'.$this->name.$this->attributes().(self::$xhtml&&$this->isEmpty?' />':'>');}else{return'';}}final
public
function
endTag(){return$this->name&&!$this->isEmpty?'</'.$this->name.'>':'';}final
public
function
attributes(){if(!is_array($this->attrs)){return'';}$s='';foreach($this->attrs
as$key=>$value){if($value===NULL||$value===FALSE)continue;if($value===TRUE){if(self::$xhtml)$s.=' '.$key.'="'.$key.'"';else$s.=' '.$key;continue;}elseif(is_array($value)){$tmp=NULL;foreach($value
as$k=>$v){if($v==NULL)continue;$tmp[]=is_string($k)?($v===TRUE?$k:$k.':'.$v):$v;}if($tmp===NULL)continue;$value=implode($key==='style'||!strncmp($key,'on',2)?';':' ',$tmp);}else{$value=(string)$value;}$s.=' '.$key.'="'.str_replace(array('&','"','<','>','@'),array('&amp;','&quot;','&lt;','&gt;','&#64;'),$value).'"';}return$s;}public
function
__clone(){foreach($this->children
as$key=>$value){if(is_object($value)){$this->children[$key]=clone$value;}}}}class
RecursiveHtmlIterator
extends
RecursiveArrayIterator
implements
Countable{public
function
getChildren(){return$this->current()->getIterator();}public
function
count(){return
iterator_count($this);}}interface
IHttpRequest{const
GET='GET',POST='POST',HEAD='HEAD',PUT='PUT',DELETE='DELETE';function
getUri();function
getQuery($key=NULL,$default=NULL);function
getPost($key=NULL,$default=NULL);function
getPostRaw();function
getFile($key);function
getFiles();function
getCookie($key,$default=NULL);function
getCookies();function
getMethod();function
isMethod($method);function
getHeader($header,$default=NULL);function
getHeaders();function
isSecured();function
isAjax();function
getRemoteAddress();function
getRemoteHost();}class
HttpRequest
extends
Object
implements
IHttpRequest{protected$query;protected$post;protected$files;protected$cookies;protected$uri;protected$originalUri;protected$headers;protected$uriFilter=array(PHP_URL_PATH=>array('#/{2,}#'=>'/'),0=>array());protected$encoding;final
public
function
getUri(){if($this->uri===NULL){$this->detectUri();}return$this->uri;}public
function
setUri(UriScript$uri){$this->uri=clone$uri;$this->query=NULL;$this->uri->canonicalize();$this->uri->freeze();return$this;}final
public
function
getOriginalUri(){if($this->originalUri===NULL){$this->detectUri();}return$this->originalUri;}public
function
addUriFilter($pattern,$replacement='',$component=NULL){$pattern='#'.$pattern.'#';$component=$component===PHP_URL_PATH?PHP_URL_PATH:0;if($replacement===NULL){unset($this->uriFilter[$component][$pattern]);}else{$this->uriFilter[$component][$pattern]=$replacement;}$this->uri=NULL;}final
public
function
getUriFilters(){return$this->uriFilter;}protected
function
detectUri(){$uri=$this->uri=new
UriScript;$uri->scheme=$this->isSecured()?'https':'http';$uri->user=isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:'';$uri->password=isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:'';if(isset($_SERVER['HTTP_HOST'])){$pair=explode(':',$_SERVER['HTTP_HOST']);}elseif(isset($_SERVER['SERVER_NAME'])){$pair=explode(':',$_SERVER['SERVER_NAME']);}else{$pair=array('');}$uri->host=$pair[0];if(isset($pair[1])){$uri->port=(int)$pair[1];}elseif(isset($_SERVER['SERVER_PORT'])){$uri->port=(int)$_SERVER['SERVER_PORT'];}if(isset($_SERVER['REQUEST_URI'])){$requestUri=$_SERVER['REQUEST_URI'];}elseif(isset($_SERVER['ORIG_PATH_INFO'])){$requestUri=$_SERVER['ORIG_PATH_INFO'];if(isset($_SERVER['QUERY_STRING'])&&$_SERVER['QUERY_STRING']!=''){$requestUri.='?'.$_SERVER['QUERY_STRING'];}}else{$requestUri='';}$tmp=explode('?',$requestUri,2);$this->originalUri=new
Uri($uri);$this->originalUri->path=$tmp[0];$this->originalUri->query=isset($tmp[1])?$tmp[1]:'';$this->originalUri->freeze();$requestUri=preg_replace(array_keys($this->uriFilter[0]),array_values($this->uriFilter[0]),$requestUri);$tmp=explode('?',$requestUri,2);$uri->path=preg_replace(array_keys($this->uriFilter[PHP_URL_PATH]),array_values($this->uriFilter[PHP_URL_PATH]),$tmp[0]);$uri->path=String::fixEncoding($uri->path);$uri->query=isset($tmp[1])?$tmp[1]:'';$uri->canonicalize();$filename=isset($_SERVER['SCRIPT_FILENAME'])?basename($_SERVER['SCRIPT_FILENAME']):NULL;$scriptPath='';if(isset($_SERVER['SCRIPT_NAME'])&&basename($_SERVER['SCRIPT_NAME'])===$filename){$scriptPath=rtrim($_SERVER['SCRIPT_NAME'],'/');}elseif(isset($_SERVER['PHP_SELF'])&&basename($_SERVER['PHP_SELF'])===$filename){$scriptPath=$_SERVER['PHP_SELF'];}elseif(isset($_SERVER['ORIG_SCRIPT_NAME'])&&basename($_SERVER['ORIG_SCRIPT_NAME'])===$filename){$scriptPath=$_SERVER['ORIG_SCRIPT_NAME'];}elseif(isset($_SERVER['PHP_SELF'],$_SERVER['SCRIPT_FILENAME'])){$path=$_SERVER['PHP_SELF'];$segs=explode('/',trim($_SERVER['SCRIPT_FILENAME'],'/'));$segs=array_reverse($segs);$index=0;$last=count($segs);do{$seg=$segs[$index];$scriptPath='/'.$seg.$scriptPath;$index++;}while(($last>$index)&&(FALSE!==($pos=strpos($path,$scriptPath)))&&(0!=$pos));}if(strncmp($uri->path,$scriptPath,strlen($scriptPath))===0){$uri->scriptPath=$scriptPath;}elseif(strncmp($uri->path,$scriptPath,strrpos($scriptPath,'/')+1)===0){$uri->scriptPath=substr($scriptPath,0,strrpos($scriptPath,'/')+1);}elseif(strpos($uri->path,basename($scriptPath))===FALSE){$uri->scriptPath='/';}elseif((strlen($uri->path)>=strlen($scriptPath))&&((FALSE!==($pos=strpos($uri->path,$scriptPath)))&&($pos!==0))){$uri->scriptPath=substr($uri->path,0,$pos+strlen($scriptPath));}else{$uri->scriptPath=$scriptPath;}$uri->freeze();}final
public
function
getQuery($key=NULL,$default=NULL){if($this->query===NULL){$this->initialize();}if(func_num_args()===0){return$this->query;}elseif(isset($this->query[$key])){return$this->query[$key];}else{return$default;}}final
public
function
getPost($key=NULL,$default=NULL){if($this->post===NULL){$this->initialize();}if(func_num_args()===0){return$this->post;}elseif(isset($this->post[$key])){return$this->post[$key];}else{return$default;}}public
function
getPostRaw(){return
file_get_contents('php://input');}final
public
function
getFile($key){if($this->files===NULL){$this->initialize();}$args=func_get_args();return
ArrayTools::get($this->files,$args);}final
public
function
getFiles(){if($this->files===NULL){$this->initialize();}return$this->files;}final
public
function
getCookie($key,$default=NULL){if($this->cookies===NULL){$this->initialize();}if(func_num_args()===0){return$this->cookies;}elseif(isset($this->cookies[$key])){return$this->cookies[$key];}else{return$default;}}final
public
function
getCookies(){if($this->cookies===NULL){$this->initialize();}return$this->cookies;}public
function
setEncoding($encoding){if(strcasecmp($encoding,$this->encoding)){$this->encoding=$encoding;$this->query=$this->post=$this->cookies=$this->files=NULL;}return$this;}public
function
initialize(){$filter=(!in_array(ini_get("filter.default"),array("","unsafe_raw"))||ini_get("filter.default_flags"));parse_str($this->getUri()->query,$this->query);if(!$this->query){$this->query=$filter?filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW):(empty($_GET)?array():$_GET);}$this->post=$filter?filter_input_array(INPUT_POST,FILTER_UNSAFE_RAW):(empty($_POST)?array():$_POST);$this->cookies=$filter?filter_input_array(INPUT_COOKIE,FILTER_UNSAFE_RAW):(empty($_COOKIE)?array():$_COOKIE);$gpc=(bool)get_magic_quotes_gpc();$enc=(bool)$this->encoding;$old=error_reporting(error_reporting()^E_NOTICE);$nonChars='#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{10FFFF}]#u';if($gpc||$enc){$utf=strcasecmp($this->encoding,'UTF-8')===0;$list=array(&$this->query,&$this->post,&$this->cookies);while(list($key,$val)=each($list)){foreach($val
as$k=>$v){unset($list[$key][$k]);if($gpc){$k=stripslashes($k);}if($enc&&is_string($k)&&(preg_match($nonChars,$k)||preg_last_error())){}elseif(is_array($v)){$list[$key][$k]=$v;$list[]=&$list[$key][$k];}else{if($gpc&&!$filter){$v=stripSlashes($v);}if($enc){if($utf){$v=String::fixEncoding($v);}else{if(!String::checkEncoding($v)){$v=iconv($this->encoding,'UTF-8//IGNORE',$v);}$v=html_entity_decode($v,ENT_NOQUOTES,'UTF-8');}$v=preg_replace($nonChars,'',$v);}$list[$key][$k]=$v;}}}unset($list,$key,$val,$k,$v);}$this->files=array();$list=array();if(!empty($_FILES)){foreach($_FILES
as$k=>$v){if($enc&&is_string($k)&&(preg_match($nonChars,$k)||preg_last_error()))continue;$v['@']=&$this->files[$k];$list[]=$v;}}while(list(,$v)=each($list)){if(!isset($v['name'])){continue;}elseif(!is_array($v['name'])){if($gpc){$v['name']=stripSlashes($v['name']);}if($enc){$v['name']=preg_replace($nonChars,'',String::fixEncoding($v['name']));}$v['@']=new
HttpUploadedFile($v);continue;}foreach($v['name']as$k=>$foo){if($enc&&is_string($k)&&(preg_match($nonChars,$k)||preg_last_error()))continue;$list[]=array('name'=>$v['name'][$k],'type'=>$v['type'][$k],'size'=>$v['size'][$k],'tmp_name'=>$v['tmp_name'][$k],'error'=>$v['error'][$k],'@'=>&$v['@'][$k]);}}error_reporting($old);}public
function
getMethod(){return
isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:NULL;}public
function
isMethod($method){return
isset($_SERVER['REQUEST_METHOD'])?strcasecmp($_SERVER['REQUEST_METHOD'],$method)===0:FALSE;}public
function
isPost(){return$this->isMethod('POST');}final
public
function
getHeader($header,$default=NULL){$header=strtolower($header);$headers=$this->getHeaders();if(isset($headers[$header])){return$headers[$header];}else{return$default;}}public
function
getHeaders(){if($this->headers===NULL){if(function_exists('apache_request_headers')){$this->headers=array_change_key_case(apache_request_headers(),CASE_LOWER);}else{$this->headers=array();foreach($_SERVER
as$k=>$v){if(strncmp($k,'HTTP_',5)==0){$this->headers[strtr(strtolower(substr($k,5)),'_','-')]=$v;}}}}return$this->headers;}final
public
function
getReferer(){$uri=self::getHeader('referer');return$uri?new
Uri($uri):NULL;}public
function
isSecured(){return
isset($_SERVER['HTTPS'])&&strcasecmp($_SERVER['HTTPS'],'off');}public
function
isAjax(){return$this->getHeader('X-Requested-With')==='XMLHttpRequest';}public
function
getRemoteAddress(){return
isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:NULL;}public
function
getRemoteHost(){if(!isset($_SERVER['REMOTE_HOST'])){if(!isset($_SERVER['REMOTE_ADDR'])){return
NULL;}$_SERVER['REMOTE_HOST']=getHostByAddr($_SERVER['REMOTE_ADDR']);}return$_SERVER['REMOTE_HOST'];}public
function
detectLanguage(array$langs){$header=$this->getHeader('accept-language');if(!$header)return
NULL;$s=strtolower($header);$s=strtr($s,'_','-');rsort($langs);preg_match_all('#('.implode('|',$langs).')(?:-[^\s,;=]+)?\s*(?:;\s*q=([0-9.]+))?#',$s,$matches);if(!$matches[0]){return
NULL;}$max=0;$lang=NULL;foreach($matches[1]as$key=>$value){$q=$matches[2][$key]===''?1.0:(float)$matches[2][$key];if($q>$max){$max=$q;$lang=$value;}}return$lang;}}interface
IHttpResponse{const
PERMANENT=2116333333;const
BROWSER=0;const
S200_OK=200,S204_NO_CONTENT=204,S300_MULTIPLE_CHOICES=300,S301_MOVED_PERMANENTLY=301,S302_FOUND=302,S303_SEE_OTHER=303,S303_POST_GET=303,S304_NOT_MODIFIED=304,S307_TEMPORARY_REDIRECT=307,S400_BAD_REQUEST=400,S401_UNAUTHORIZED=401,S403_FORBIDDEN=403,S404_NOT_FOUND=404,S405_METHOD_NOT_ALLOWED=405,S410_GONE=410,S500_INTERNAL_SERVER_ERROR=500,S501_NOT_IMPLEMENTED=501,S503_SERVICE_UNAVAILABLE=503;function
setCode($code);function
getCode();function
setHeader($name,$value);function
addHeader($name,$value);function
setContentType($type,$charset=NULL);function
redirect($url,$code=self::S302_FOUND);function
expire($seconds);function
isSent();function
getHeaders();function
setCookie($name,$value,$expire,$path=NULL,$domain=NULL,$secure=NULL);function
deleteCookie($name,$path=NULL,$domain=NULL,$secure=NULL);}final
class
HttpResponse
extends
Object
implements
IHttpResponse{private
static$fixIE=TRUE;public$cookieDomain='';public$cookiePath='/';public$cookieSecure=FALSE;private$code=self::S200_OK;public
function
setCode($code){$code=(int)$code;static$allowed=array(200=>1,201=>1,202=>1,203=>1,204=>1,205=>1,206=>1,300=>1,301=>1,302=>1,303=>1,304=>1,307=>1,400=>1,401=>1,403=>1,404=>1,406=>1,408=>1,410=>1,412=>1,415=>1,416=>1,500=>1,501=>1,503=>1,505=>1);if(!isset($allowed[$code])){throw
new
InvalidArgumentException("Bad HTTP response '$code'.");}elseif(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot set HTTP code after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}else{$this->code=$code;$protocol=isset($_SERVER['SERVER_PROTOCOL'])?$_SERVER['SERVER_PROTOCOL']:'HTTP/1.1';header($protocol.' '.$code,TRUE,$code);}return$this;}public
function
getCode(){return$this->code;}public
function
setHeader($name,$value){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot send header after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}if($value===NULL&&function_exists('header_remove')){header_remove($name);}else{header($name.': '.$value,TRUE,$this->code);}return$this;}public
function
addHeader($name,$value){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot send header after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}header($name.': '.$value,FALSE,$this->code);}public
function
setContentType($type,$charset=NULL){$this->setHeader('Content-Type',$type.($charset?'; charset='.$charset:''));return$this;}public
function
redirect($url,$code=self::S302_FOUND){if(isset($_SERVER['SERVER_SOFTWARE'])&&preg_match('#^Microsoft-IIS/[1-5]#',$_SERVER['SERVER_SOFTWARE'])&&$this->getHeader('Set-Cookie')!==NULL){$this->setHeader('Refresh',"0;url=$url");return;}$this->setCode($code);$this->setHeader('Location',$url);echo"<h1>Redirect</h1>\n\n<p><a href=\"".htmlSpecialChars($url)."\">Please click here to continue</a>.</p>";}public
function
expire($seconds){if(is_string($seconds)&&!is_numeric($seconds)){$seconds=strtotime($seconds);}if($seconds>0){if($seconds<=Tools::YEAR){$seconds+=time();}$this->setHeader('Cache-Control','max-age='.($seconds-time()));$this->setHeader('Expires',self::date($seconds));}else{$this->setHeader('Expires','Mon, 23 Jan 1978 10:00:00 GMT');$this->setHeader('Cache-Control','s-maxage=0, max-age=0, must-revalidate');}}public
function
isSent(){return
headers_sent();}public
function
getHeader($header,$default=NULL){$header.=':';$len=strlen($header);foreach(headers_list()as$item){if(strncasecmp($item,$header,$len)===0){return
ltrim(substr($item,$len));}}return$default;}public
function
getHeaders(){$headers=array();foreach(headers_list()as$header){$a=strpos($header,':');$headers[substr($header,0,$a)]=(string)substr($header,$a+2);}return$headers;}public
static
function
date($time=NULL){return
gmdate('D, d M Y H:i:s \G\M\T',$time===NULL?time():$time);}public
function
enableCompression(){if(headers_sent()){return
FALSE;}if($this->getHeader('Content-Encoding')!==NULL){return
FALSE;}$ok=ob_gzhandler('',PHP_OUTPUT_HANDLER_START);if($ok===FALSE){return
FALSE;}if(function_exists('ini_set')){ini_set('zlib.output_compression','Off');ini_set('zlib.output_compression_level','6');}ob_start('ob_gzhandler',1);return
TRUE;}public
function
__destruct(){if(self::$fixIE){if(!isset($_SERVER['HTTP_USER_AGENT'])||strpos($_SERVER['HTTP_USER_AGENT'],'MSIE ')===FALSE)return;if(!in_array($this->code,array(400,403,404,405,406,408,409,410,500,501,505),TRUE))return;if($this->getHeader('Content-Type','text/html')!=='text/html')return;$s=" \t\r\n";for($i=2e3;$i;$i--)echo$s{rand(0,3)};self::$fixIE=FALSE;}}public
function
setCookie($name,$value,$expire,$path=NULL,$domain=NULL,$secure=NULL){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot set cookie after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}if(is_string($expire)&&!is_numeric($expire)){$expire=strtotime($expire);}elseif($expire>0&&$expire<=Tools::YEAR){$expire+=time();}setcookie($name,$value,$expire,$path===NULL?$this->cookiePath:(string)$path,$domain===NULL?$this->cookieDomain:(string)$domain,$secure===NULL?$this->cookieSecure:(bool)$secure,TRUE);return$this;}public
function
deleteCookie($name,$path=NULL,$domain=NULL,$secure=NULL){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot delete cookie after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}setcookie($name,FALSE,254400000,$path===NULL?$this->cookiePath:(string)$path,$domain===NULL?$this->cookieDomain:(string)$domain,$secure===NULL?$this->cookieSecure:(bool)$secure,TRUE);}}class
HttpUploadedFile
extends
Object{private$name;private$type;private$size;private$tmpName;private$error;public
function
__construct($value){foreach(array('name','type','size','tmp_name','error')as$key){if(!isset($value[$key])||!is_scalar($value[$key])){$this->error=UPLOAD_ERR_NO_FILE;return;}}$this->name=$value['name'];$this->size=$value['size'];$this->tmpName=$value['tmp_name'];$this->error=$value['error'];}public
function
getName(){return$this->name;}public
function
getContentType(){if($this->isOk()&&$this->type===NULL){$info=getimagesize($this->tmpName);if(isset($info['mime'])){$this->type=$info['mime'];}elseif(extension_loaded('fileinfo')){$this->type=finfo_file(finfo_open(FILEINFO_MIME),$this->tmpName);}elseif(function_exists('mime_content_type')){$this->type=mime_content_type($this->tmpName);}if(!$this->type){$this->type='application/octet-stream';}}return$this->type;}public
function
getSize(){return$this->size;}public
function
getTemporaryFile(){return$this->tmpName;}public
function
__toString(){return$this->tmpName;}public
function
getError(){return$this->error;}public
function
isOk(){return$this->error===UPLOAD_ERR_OK;}public
function
move($dest){if(!move_uploaded_file($this->tmpName,$dest)){throw
new
InvalidStateException("Unable to move uploaded file '$this->tmpName' to '$dest'.");}$this->tmpName=$dest;return
TRUE;}public
function
isImage(){return
in_array($this->getContentType(),array('image/gif','image/png','image/jpeg'),TRUE);}public
function
getImage(){return
Image::fromFile($this->tmpName);}public
function
getImageSize(){return$this->isOk()?getimagesize($this->tmpName):NULL;}}interface
IUser{function
authenticate($username,$password,$extra=NULL);function
signOut($clearIdentity=FALSE);function
isAuthenticated();function
getIdentity();function
setAuthenticationHandler(IAuthenticator$handler);function
getAuthenticationHandler();function
setNamespace($namespace);function
getNamespace();function
getRoles();function
isInRole($role);function
isAllowed();function
setAuthorizationHandler(IAuthorizator$handler);function
getAuthorizationHandler();}class
Session
extends
Object{const
DEFAULT_FILE_LIFETIME=10800;public$verificationKeyGenerator;private$regenerationNeeded;private
static$started;private$options=array('referer_check'=>'','use_cookies'=>1,'use_only_cookies'=>1,'use_trans_sid'=>0,'cookie_lifetime'=>0,'cookie_path'=>'/','cookie_domain'=>'','cookie_secure'=>FALSE,'cookie_httponly'=>TRUE,'gc_maxlifetime'=>self::DEFAULT_FILE_LIFETIME,'cache_limiter'=>NULL,'cache_expire'=>NULL,'hash_function'=>NULL,'hash_bits_per_character'=>NULL);public
function
__construct(){$this->verificationKeyGenerator=array($this,'generateVerificationKey');}public
function
start(){if(self::$started){throw
new
InvalidStateException('Session has already been started.');}elseif(self::$started===NULL&&defined('SID')){throw
new
InvalidStateException('A session had already been started by session.auto-start or session_start().');}if($this->verificationKeyGenerator){fixCallback($this->verificationKeyGenerator);if(!is_callable($this->verificationKeyGenerator)){$able=is_callable($this->verificationKeyGenerator,TRUE,$textual);throw
new
InvalidStateException("Verification key generator '$textual' is not ".($able?'callable.':'valid PHP callback.'));}}try{$this->configure($this->options);}catch(NotSupportedException$e){}Tools::tryError();session_start();if(Tools::catchError($msg)){@session_write_close();throw
new
InvalidStateException($msg);}self::$started=TRUE;if($this->regenerationNeeded){session_regenerate_id(TRUE);$this->regenerationNeeded=FALSE;}$verKey=$this->verificationKeyGenerator?(string)call_user_func($this->verificationKeyGenerator):NULL;if(!isset($_SESSION['__NT']['V'])){$_SESSION['__NT']=array();$_SESSION['__NT']['C']=0;$_SESSION['__NT']['V']=$verKey;}else{$saved=&$_SESSION['__NT']['V'];if($verKey==NULL||$verKey===$saved){$_SESSION['__NT']['C']++;}else{session_regenerate_id(TRUE);$_SESSION=array();$_SESSION['__NT']['C']=0;$_SESSION['__NT']['V']=$verKey;}}$browserKey=$this->getHttpRequest()->getCookie('nette-browser');if(!$browserKey){$browserKey=(string)lcg_value();}$browserClosed=!isset($_SESSION['__NT']['B'])||$_SESSION['__NT']['B']!==$browserKey;$_SESSION['__NT']['B']=$browserKey;$this->sendCookie();if(isset($_SESSION['__NM'])){$now=time();foreach($_SESSION['__NM']as$namespace=>$metadata){if(isset($metadata['EXP'])){foreach($metadata['EXP']as$variable=>$value){if(!is_array($value))$value=array($value,!$value);list($time,$whenBrowserIsClosed)=$value;if(($whenBrowserIsClosed&&$browserClosed)||($time&&$now>$time)){if($variable===''){unset($_SESSION['__NM'][$namespace],$_SESSION['__NS'][$namespace]);continue
2;}unset($_SESSION['__NS'][$namespace][$variable],$_SESSION['__NM'][$namespace]['EXP'][$variable]);}}}}}register_shutdown_function(array($this,'clean'));}public
function
isStarted(){return(bool)self::$started;}public
function
close(){if(self::$started){session_write_close();self::$started=FALSE;}}public
function
destroy(){if(!self::$started){throw
new
InvalidStateException('Session is not started.');}session_destroy();$_SESSION=NULL;self::$started=FALSE;if(!$this->getHttpResponse()->isSent()){$params=session_get_cookie_params();$this->getHttpResponse()->deleteCookie(session_name(),$params['path'],$params['domain'],$params['secure']);}}public
function
exists(){return
self::$started||$this->getHttpRequest()->getCookie(session_name())!==NULL;}public
function
regenerateId(){if(self::$started){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot regenerate session ID after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}session_regenerate_id(TRUE);}else{$this->regenerationNeeded=TRUE;}}public
function
getId(){return
session_id();}public
function
setName($name){if(!is_string($name)||!preg_match('#[^0-9.][^.]*$#A',$name)){throw
new
InvalidArgumentException('Session name must be a string and cannot contain dot.');}session_name($name);return$this->setOptions(array('name'=>$name));}public
function
getName(){return
session_name();}public
function
generateVerificationKey(){$httpRequest=$this->getHttpRequest();$key[]=$httpRequest->getHeader('Accept-Charset');$key[]=$httpRequest->getHeader('Accept-Encoding');$key[]=$httpRequest->getHeader('Accept-Language');$key[]=$httpRequest->getHeader('User-Agent');if(strpos($key[3],'MSIE 8.0')){$key[2]=substr($key[2],0,2);}return
md5(implode("\0",$key));}public
function
getNamespace($namespace,$class='SessionNamespace'){if(!is_string($namespace)||$namespace===''){throw
new
InvalidArgumentException('Session namespace must be a non-empty string.');}if(!self::$started){$this->start();}return
new$class($_SESSION['__NS'][$namespace],$_SESSION['__NM'][$namespace]);}public
function
hasNamespace($namespace){if($this->exists()&&!self::$started){$this->start();}return!empty($_SESSION['__NS'][$namespace]);}public
function
getIterator(){if($this->exists()&&!self::$started){$this->start();}if(isset($_SESSION['__NS'])){return
new
ArrayIterator(array_keys($_SESSION['__NS']));}else{return
new
ArrayIterator;}}public
function
clean(){if(!self::$started||empty($_SESSION)){return;}if(isset($_SESSION['__NM'])&&is_array($_SESSION['__NM'])){foreach($_SESSION['__NM']as$name=>$foo){if(empty($_SESSION['__NM'][$name]['EXP'])){unset($_SESSION['__NM'][$name]['EXP']);}if(empty($_SESSION['__NM'][$name])){unset($_SESSION['__NM'][$name]);}}}if(empty($_SESSION['__NM'])){unset($_SESSION['__NM']);}if(empty($_SESSION['__NS'])){unset($_SESSION['__NS']);}if(empty($_SESSION)){}}public
function
setOptions(array$options){if(self::$started){$this->configure($options);}$this->options=$options+$this->options;return$this;}public
function
getOptions(){return$this->options;}private
function
configure(array$config){$special=array('cache_expire'=>1,'cache_limiter'=>1,'save_path'=>1,'name'=>1);foreach($config
as$key=>$value){if(!strncmp($key,'session.',8)){$key=substr($key,8);}if($value===NULL){continue;}elseif(isset($special[$key])){if(self::$started){throw
new
InvalidStateException("Unable to set '$key' when session has been started.");}$key="session_$key";$key($value);}elseif(strncmp($key,'cookie_',7)===0){if(!isset($cookie)){$cookie=session_get_cookie_params();}$cookie[substr($key,7)]=$value;}elseif(!function_exists('ini_set')){if(ini_get($key)!=$value){throw
new
NotSupportedException('Required function ini_set() is disabled.');}}else{if(self::$started){throw
new
InvalidStateException("Unable to set '$key' when session has been started.");}ini_set("session.$key",$value);}}if(isset($cookie)){session_set_cookie_params($cookie['lifetime'],$cookie['path'],$cookie['domain'],$cookie['secure'],$cookie['httponly']);if(self::$started){$this->sendCookie();}}}public
function
setExpiration($seconds){if(is_string($seconds)&&!is_numeric($seconds)){$seconds=strtotime($seconds);}if($seconds<=0){return$this->setOptions(array('gc_maxlifetime'=>self::DEFAULT_FILE_LIFETIME,'cookie_lifetime'=>0));}else{if($seconds>Tools::YEAR){$seconds-=time();}return$this->setOptions(array('gc_maxlifetime'=>$seconds,'cookie_lifetime'=>$seconds));}}public
function
setCookieParams($path,$domain=NULL,$secure=NULL){return$this->setOptions(array('cookie_path'=>$path,'cookie_domain'=>$domain,'cookie_secure'=>$secure));}public
function
getCookieParams(){return
session_get_cookie_params();}public
function
setSavePath($path){return$this->setOptions(array('save_path'=>$path));}private
function
sendCookie(){$cookie=$this->getCookieParams();$this->getHttpResponse()->setCookie(session_name(),session_id(),$cookie['lifetime'],$cookie['path'],$cookie['domain'],$cookie['secure'],$cookie['httponly']);$this->getHttpResponse()->setCookie('nette-browser',$_SESSION['__NT']['B'],HttpResponse::BROWSER,$cookie['path'],$cookie['domain'],$cookie['secure'],$cookie['httponly']);}protected
function
getHttpRequest(){return
Environment::getHttpRequest();}protected
function
getHttpResponse(){return
Environment::getHttpResponse();}}final
class
SessionNamespace
extends
Object
implements
IteratorAggregate,ArrayAccess{private$data;private$meta;public$warnOnUndefined=FALSE;public
function
__construct(&$data,&$meta){$this->data=&$data;$this->meta=&$meta;}public
function
getIterator(){if(isset($this->data)){return
new
ArrayIterator($this->data);}else{return
new
ArrayIterator;}}public
function
__set($name,$value){$this->data[$name]=$value;}public
function&__get($name){if($this->warnOnUndefined&&!array_key_exists($name,$this->data)){trigger_error("The variable '$name' does not exist in session namespace",E_USER_NOTICE);}return$this->data[$name];}public
function
__isset($name){return
isset($this->data[$name]);}public
function
__unset($name){unset($this->data[$name],$this->meta['EXP'][$name]);}public
function
offsetSet($name,$value){$this->__set($name,$value);}public
function
offsetGet($name){return$this->__get($name);}public
function
offsetExists($name){return$this->__isset($name);}public
function
offsetUnset($name){$this->__unset($name);}public
function
setExpiration($seconds,$variables=NULL){if(is_string($seconds)&&!is_numeric($seconds)){$seconds=strtotime($seconds);}$whenBrowserIsClosed=$seconds==0;if($seconds<=0){$seconds=0;}elseif($seconds<=Tools::YEAR){$seconds+=time();}if($variables===NULL){$this->meta['EXP']['']=array($seconds,$whenBrowserIsClosed);}elseif(is_array($variables)){foreach($variables
as$variable){$this->meta['EXP'][$variable]=array($seconds,$whenBrowserIsClosed);}}else{$this->meta['EXP'][$variables]=array($seconds,$whenBrowserIsClosed);}return$this;}public
function
removeExpiration($variables=NULL){if($variables===NULL){unset($this->meta['EXP']['']);}elseif(is_array($variables)){foreach($variables
as$variable){unset($this->meta['EXP'][$variable]);}}else{unset($this->meta['EXP'][$variables]);}}public
function
remove(){$this->data=NULL;$this->meta=NULL;}}class
Uri
extends
FreezableObject{public
static$defaultPorts=array('http'=>80,'https'=>443,'ftp'=>21,'news'=>119,'nntp'=>119);private$scheme='';private$user='';private$pass='';private$host='';private$port=NULL;private$path='';private$query='';private$fragment='';public
function
__construct($uri=NULL){if(is_string($uri)){$parts=@parse_url($uri);if($parts===FALSE){throw
new
InvalidArgumentException("Malformed or unsupported URI '$uri'.");}foreach($parts
as$key=>$val){$this->$key=$val;}if(!$this->port&&isset(self::$defaultPorts[$this->scheme])){$this->port=self::$defaultPorts[$this->scheme];}}elseif($uri
instanceof
self){foreach($uri
as$key=>$val){$this->$key=$val;}}}public
function
setScheme($value){$this->updating();$this->scheme=(string)$value;return$this;}public
function
getScheme(){return$this->scheme;}public
function
setUser($value){$this->updating();$this->user=(string)$value;return$this;}public
function
getUser(){return$this->user;}public
function
setPassword($value){$this->updating();$this->pass=(string)$value;return$this;}public
function
getPassword(){return$this->pass;}public
function
setPass($value){$this->setPassword($value);}public
function
getPass(){return$this->pass;}public
function
setHost($value){$this->updating();$this->host=(string)$value;return$this;}public
function
getHost(){return$this->host;}public
function
setPort($value){$this->updating();$this->port=(int)$value;return$this;}public
function
getPort(){return$this->port;}public
function
setPath($value){$this->updating();$this->path=(string)$value;return$this;}public
function
getPath(){return$this->path;}public
function
setQuery($value){$this->updating();$this->query=(string)(is_array($value)?http_build_query($value,'','&'):$value);return$this;}public
function
appendQuery($value){$this->updating();$value=(string)(is_array($value)?http_build_query($value,'','&'):$value);$this->query.=($this->query===''||$value==='')?$value:'&'.$value;}public
function
getQuery(){return$this->query;}public
function
setFragment($value){$this->updating();$this->fragment=(string)$value;return$this;}public
function
getFragment(){return$this->fragment;}public
function
getAbsoluteUri(){return$this->scheme.'://'.$this->getAuthority().$this->path.($this->query===''?'':'?'.$this->query).($this->fragment===''?'':'#'.$this->fragment);}public
function
getAuthority(){$authority=$this->host;if($this->port&&isset(self::$defaultPorts[$this->scheme])&&$this->port!==self::$defaultPorts[$this->scheme]){$authority.=':'.$this->port;}if($this->user!==''&&$this->scheme!=='http'&&$this->scheme!=='https'){$authority=$this->user.($this->pass===''?'':':'.$this->pass).'@'.$authority;}return$authority;}public
function
getHostUri(){return$this->scheme.'://'.$this->getAuthority();}public
function
isEqual($uri){$part=self::unescape(strtok($uri,'?#'),'%/');if(strncmp($part,'//',2)===0){if($part!=='//'.$this->getAuthority().$this->path)return
FALSE;}elseif(strncmp($part,'/',1)===0){if($part!==$this->path)return
FALSE;}else{if($part!==$this->scheme.'://'.$this->getAuthority().$this->path)return
FALSE;}$part=(string)strtok('?#');if($part!==''){$tmp=preg_split('#[&;]#',self::unescape(strtr($part,'+',' '),'%&;=+'));sort($tmp);$part=implode('&',$tmp);}return$part===$this->query;}public
function
canonicalize(){$this->updating();$this->path=$this->path===''?'/':self::unescape($this->path,'%/');$this->host=strtolower(rawurldecode($this->host));if($this->query!==''){$tmp=preg_split('#[&;]#',self::unescape(strtr($this->query,'+',' '),'%&;=+'));sort($tmp);$this->query=implode('&',$tmp);}}public
function
__toString(){return$this->getAbsoluteUri();}public
static
function
unescape($s,$reserved='%;/?:@&=+$,'){preg_match_all('#(?<=%)[a-f0-9][a-f0-9]#i',$s,$matches,PREG_OFFSET_CAPTURE|PREG_SET_ORDER);foreach(array_reverse($matches)as$match){$ch=chr(hexdec($match[0][0]));if(strpos($reserved,$ch)===FALSE){$s=substr_replace($s,$ch,$match[0][1]-1,3);}}return$s;}}class
UriScript
extends
Uri{private$scriptPath='';public
function
setScriptPath($value){$this->updating();$this->scriptPath=(string)$value;return$this;}public
function
getScriptPath(){return$this->scriptPath;}public
function
getBasePath(){return(string)substr($this->scriptPath,0,strrpos($this->scriptPath,'/')+1);}public
function
getBaseUri(){return$this->scheme.'://'.$this->getAuthority().$this->getBasePath();}public
function
getRelativeUri(){return(string)substr($this->path,strrpos($this->scriptPath,'/')+1);}public
function
getPathInfo(){return(string)substr($this->path,strlen($this->scriptPath));}}class
User
extends
Object
implements
IUser{const
MANUAL=1;const
INACTIVITY=2;const
BROWSER_CLOSED=3;public$guestRole='guest';public$authenticatedRole='authenticated';public$onAuthenticated;public$onSignedOut;private$authenticationHandler;private$authorizationHandler;private$namespace='';private$session;public
function
authenticate($username,$password,$extra=NULL){$handler=$this->getAuthenticationHandler();if($handler===NULL){throw
new
InvalidStateException('Authentication handler has not been set.');}$this->signOut(TRUE);$credentials=array(IAuthenticator::USERNAME=>$username,IAuthenticator::PASSWORD=>$password,'extra'=>$extra);$this->setIdentity($handler->authenticate($credentials));$this->setAuthenticated(TRUE);$this->onAuthenticated($this);}final
public
function
signOut($clearIdentity=FALSE){if($this->isAuthenticated()){$this->setAuthenticated(FALSE);$this->onSignedOut($this);}if($clearIdentity){$this->setIdentity(NULL);}}final
public
function
isAuthenticated(){$session=$this->getSessionNamespace(FALSE);return$session&&$session->authenticated;}final
public
function
getIdentity(){$session=$this->getSessionNamespace(FALSE);return$session?$session->identity:NULL;}public
function
setAuthenticationHandler(IAuthenticator$handler){$this->authenticationHandler=$handler;return$this;}final
public
function
getAuthenticationHandler(){if($this->authenticationHandler===NULL){$this->authenticationHandler=Environment::getService('Nette\Security\IAuthenticator');}return$this->authenticationHandler;}public
function
setNamespace($namespace){if($this->namespace!==$namespace){$this->namespace=(string)$namespace;$this->session=NULL;}return$this;}final
public
function
getNamespace(){return$this->namespace;}public
function
setExpiration($seconds,$whenBrowserIsClosed=TRUE,$clearIdentity=FALSE){if(is_string($seconds)&&!is_numeric($seconds)){$seconds=strtotime($seconds);}$session=$this->getSessionNamespace(TRUE);if($seconds>0){if($seconds<=Tools::YEAR){$seconds+=time();}$session->expireTime=$seconds;$session->expireDelta=$seconds-time();}else{unset($session->expireTime,$session->expireDelta);}$session->expireIdentity=(bool)$clearIdentity;$session->expireBrowser=(bool)$whenBrowserIsClosed;$session->browserCheck=TRUE;$session->setExpiration(0,'browserCheck');return$this;}final
public
function
getSignOutReason(){$session=$this->getSessionNamespace(FALSE);return$session?$session->reason:NULL;}protected
function
getSessionNamespace($need){if($this->session!==NULL){return$this->session;}$sessionHandler=$this->getSession();if(!$need&&!$sessionHandler->exists()){return
NULL;}$this->session=$session=$sessionHandler->getNamespace('Nette.Web.User/'.$this->namespace);if(!($session->identity
instanceof
IIdentity)||!is_bool($session->authenticated)){$session->remove();}if($session->authenticated&&$session->expireBrowser&&!$session->browserCheck){$session->reason=self::BROWSER_CLOSED;$session->authenticated=FALSE;$this->onSignedOut($this);if($session->expireIdentity){unset($session->identity);}}if($session->authenticated&&$session->expireDelta>0){if($session->expireTime<time()){$session->reason=self::INACTIVITY;$session->authenticated=FALSE;$this->onSignedOut($this);if($session->expireIdentity){unset($session->identity);}}$session->expireTime=time()+$session->expireDelta;}if(!$session->authenticated){unset($session->expireTime,$session->expireDelta,$session->expireIdentity,$session->expireBrowser,$session->browserCheck,$session->authTime);}return$this->session;}protected
function
setAuthenticated($state){$session=$this->getSessionNamespace(TRUE);$session->authenticated=(bool)$state;$this->getSession()->regenerateId();if($state){$session->reason=NULL;$session->authTime=time();}else{$session->reason=self::MANUAL;$session->authTime=NULL;}return$this;}protected
function
setIdentity(IIdentity$identity=NULL){$this->getSessionNamespace(TRUE)->identity=$identity;return$this;}public
function
getRoles(){if(!$this->isAuthenticated()){return
array($this->guestRole);}$identity=$this->getIdentity();return$identity?$identity->getRoles():array($this->authenticatedRole);}final
public
function
isInRole($role){return
in_array($role,$this->getRoles(),TRUE);}public
function
isAllowed($resource=NULL,$privilege=NULL){$handler=$this->getAuthorizationHandler();if(!$handler){throw
new
InvalidStateException("Authorization handler has not been set.");}foreach($this->getRoles()as$role){if($handler->isAllowed($role,$resource,$privilege))return
TRUE;}return
FALSE;}public
function
setAuthorizationHandler(IAuthorizator$handler){$this->authorizationHandler=$handler;return$this;}final
public
function
getAuthorizationHandler(){if($this->authorizationHandler===NULL){$this->authorizationHandler=Environment::getService('Nette\Security\IAuthorizator');}return$this->authorizationHandler;}protected
function
getSession(){return
Environment::getSession();}}