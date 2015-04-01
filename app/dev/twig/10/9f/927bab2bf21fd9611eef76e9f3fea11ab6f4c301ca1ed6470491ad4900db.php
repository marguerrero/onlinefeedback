<?php

/* AdminLoginBundle:Default:index.html.twig */
class __TwigTemplate_109f927bab2bf21fd9611eef76e9f3fea11ab6f4c301ca1ed6470491ad4900db extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"utf-8\">
    <title>Concentrix Online - Admin Login</title>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <link href=\"//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css\" rel=\"stylesheet\" id=\"bootstrap-css\">
    <style type=\"text/css\">
    body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #ddd;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
  background-color: #f3f3f3;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  position: relative;
  font: 15px 'Segoe UI',Arial,sans-serif;
  background-color: #EAEBE5;
  height: auto;
  padding: 10px;
  color:#7e8c8d;
  outline:none;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type=\"email\"] {
  margin-bottom: -1px;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
.form-signin input[type=\"password\"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

#recover input[type=\"email\"] {
  margin-bottom: 10px;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

/*___________________________________*/
.container {
  border-top: 2px solid #aaa;
  box-shadow:  0 2px 10px rgba(0,0,0,0.8);
  width:288px;
  height:321px;
  margin:0 auto;
  position:relative;
  z-index:1;
  
  -moz-perspective: 800px;
  -webkit-perspective: 800px;
  perspective: 800px;
}

.container form {
  width:100%;
  height:100%;
  position:absolute;
  top:0;
  left:0;
  
  /* Enabling 3d space for the transforms */
  -moz-transform-style: preserve-3d;
  -webkit-transform-style: preserve-3d;
  transform-style: preserve-3d;
  
  /* When the forms are flipped, they will be hidden */
  -moz-backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  
  /* Enabling a smooth animated transition */
  -moz-transition:0.8s;
  -webkit-transition:0.8s;
  transition:0.8s;

}


.container.flipped .form-signin{
  
  opacity:0;
  
  /**
   * Rotating the login form when the
   * flipped class is added to the container
   */
  
  -moz-transform:rotateY(-180deg);
  -webkit-transform:rotateY(-180deg);
  transform:rotateY(-180deg);
}

.container.flipped #recover{
  
  opacity:1;
  
  /* Rotating the recover div into view */
  -moz-transform:rotateY(0deg);
  -webkit-transform:rotateY(0deg);
  transform:rotateY(0deg);
}


.form-signin {
  z-index:100;
}

  
  /* Enabling 3d space for the transforms */
  -moz-transform-style: preserve-3d;
  -webkit-transform-style: preserve-3d;
  transform-style: preserve-3d;
  
  /* When the forms are flipped, they will be hidden */
  -moz-backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  
  /* Enabling a smooth animated transition */
  -moz-transition:0.8s;
  -webkit-transition:0.8s;
  transition:0.8s;

}

#login{
  background: #f3f3f3;
  z-index: 100;
}

#recover:before {
  /* The \"Click here\" tooltip */
  width:100px;
  height:26px;
  content:'Sign in ->';
  position:absolute;
  right:270px;
  top:15px;
}

#login:after {
  /* The \"Click here\" tooltip */
  width:150px;
  height:26px;
  position:absolute;
  right:-170px;
  top:15px;
}

#recover{
  background:#f3f3f3;
  z-index:1;
  
  /* Rotating the recover password form by default */
  -moz-transform:rotateY(180deg);
  -webkit-transform:rotateY(180deg);
  transform:rotateY(180deg);
}

#formContainer.flipped #login{
  
  opacity:0;
  
  /**
   * Rotating the login form when the
   * flipped class is added to the container
   */
  
  -moz-transform:rotateY(-180deg);
  -webkit-transform:rotateY(-180deg);
  transform:rotateY(-180deg);
}

#formContainer.flipped #recover{
  
  opacity:1;
  
  /* Rotating the recover div into view */
  -moz-transform:rotateY(0deg);
  -webkit-transform:rotateY(0deg);
  transform:rotateY(0deg);
}


/*----------------------------
  Inputs, Buttons & Links
-----------------------------*/


#login .flipLink,
#recover .flipLink{  
  height: 50px;
  overflow: hidden;
  position: absolute;
  right: 0;
  text-indent: -9999px;
  top: 0;
  width: 50px;
}

#triangle-topright {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  width: 0;
  height: 0;
  border-top: 100px solid #ddd; 
  border-left: 100px solid transparent;
}

#triangle-topleft {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  right:auto;
  left:0;
  width: 0;
  height: 0;
  border-top: 50px solid #ddd;
  border-right: 50px solid transparent;     
}

#recover .flipLink{
  right:auto;
  left:0;
}

#forkongithub a {
  box-sizing: content-box;
  background:#ddd;
  color:#fff;
  text-decoration:none;
  font-family:arial, sans-serif;
  text-align:center;
  font-weight:bold;
  padding:5px 40px;
  font-size:1rem;
  line-height:2rem;
  position:relative;
  transition:0.5s;
}

#forkongithub a:hover {
  background:#aaa;
  color:#fff;
}

#forkongithub a::before, #forkongithub a::after {
  content:\"\";
  width:100%;
  display:block;
  position:absolute;
  top:1px;
  left:0;
  height:1px;
  background:#fff;
}

#forkongithub a::after {
  bottom:1px;
  top:auto;
}

@media screen and (min-width:800px){
  #forkongithub {
    position:absolute;
    display:block;
    top:0;
    right:0;
    width:200px;
    overflow:hidden;
    height:200px;
  }

  #forkongithub a {
    width:200px;
    position:absolute;
    top:60px;
    right:-60px;
    transform:rotate(45deg);
      -webkit-transform:rotate(45deg);
    box-shadow:2px 2px 10px rgba(0,0,0,0.8);
  }
}    </style>
    <script src=\"//code.jquery.com/jquery-1.10.2.min.js\"></script>
    <script src=\"//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js\"></script>
    <script type=\"text/javascript\">
        window.alert = function(){};
        var defaultCSS = document.getElementById('bootstrap-css');
        function changeCSS(css){
            if(css) \$('head > link').filter(':first').replaceWith('<link rel=\"stylesheet\" href=\"'+ css +'\" type=\"text/css\" />'); 
            else \$('head > link').filter(':first').replaceWith(defaultCSS); 
        }
        \$( document ).ready(function() {
          var iframe_height = parseInt(\$('html').height()); 
          window.parent.postMessage( iframe_height, 'http://bootsnipp.com');
        });
    </script>
</head>
<body>
\t<!--Inspired by http://tutorialzine.com/2012/02/apple-like-login-form/ - Apple-like Login Form with CSS 3D Transforms -->

<div class=\"container\">
\t<div class=\"row\">
    \t<div class=\"container\" id=\"formContainer\">

          <form class=\"form-signin\" method=\"POST\" id=\"login\" role=\"form\" action=\"";
        // line 342
        echo $this->env->getExtension('routing')->getPath("_cx_admin_check");
        echo " \">
            <h3 class=\"form-signin-heading\">Administrator Login</h3>
            <a href=\"#\" id=\"flipToRecover\" class=\"flipLink\">
              <div id=\"triangle-topright\"></div>
            </a>
            <input type=\"text\" class=\"form-control\" name=\"username\" id=\"username\" placeholder=\"Username\" required autofocus>
            <input type=\"password\" class=\"form-control\" name=\"password\" id=\"password\" placeholder=\"Password\" required>
         <input type=\"submit\" class=\"btn btn-lg btn-success btn-block\" value=\"Login\">
            <!-- <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Sign in</button> -->
          </form>
    
          

        </div> <!-- /container -->
\t</div>
</div>
\t<script type=\"text/javascript\">
\t\$(function(){
    
\t// Checking for CSS 3D transformation support
\t\$.support.css3d = supportsCSS3D();
\t
\tvar formContainer = \$('#formContainer');
\t
\t// Listening for clicks on the ribbon links
\t\$('.flipLink').click(function(e){
\t\t
\t\t// Flipping the forms
\t\tformContainer.toggleClass('flipped');
\t\t
\t\t// If there is no CSS3 3D support, simply
\t\t// hide the login form (exposing the recover one)
\t\tif(!\$.support.css3d){
\t\t\t\$('#login').toggle();
\t\t}
\t\te.preventDefault();
\t});
\t
\t// formContainer.find('form').submit(function(e){
\t// \t// Preventing form submissions. If you implement
\t// \t// a backend, you might want to remove this code
\t// \te.preventDefault();
\t// });
\t
\t
\t// A helper function that checks for the 
\t// support of the 3D CSS3 transformations.
\tfunction supportsCSS3D() {
\t\tvar props = [
\t\t\t'perspectiveProperty', 'WebkitPerspective', 'MozPerspective'
\t\t], testDom = document.createElement('a');
\t\t  
\t\tfor(var i=0; i<props.length; i++){
\t\t\tif(props[i] in testDom.style){
\t\t\t\treturn true;
\t\t\t}
\t\t}
\t\t
\t\treturn false;
\t}
});
\t</script>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "AdminLoginBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  20 => 1,  34 => 5,  124 => 65,  84 => 27,  110 => 28,  480 => 162,  474 => 161,  469 => 158,  461 => 155,  457 => 153,  453 => 151,  444 => 149,  440 => 148,  437 => 147,  435 => 146,  430 => 144,  427 => 143,  423 => 142,  413 => 134,  409 => 132,  407 => 131,  402 => 130,  398 => 129,  393 => 126,  387 => 122,  384 => 121,  381 => 120,  379 => 119,  374 => 116,  368 => 112,  365 => 111,  362 => 342,  360 => 109,  355 => 106,  341 => 105,  337 => 103,  322 => 101,  314 => 99,  312 => 98,  309 => 97,  305 => 95,  298 => 91,  294 => 90,  285 => 89,  283 => 88,  278 => 86,  268 => 85,  264 => 84,  258 => 81,  252 => 80,  247 => 78,  241 => 77,  229 => 73,  220 => 70,  214 => 69,  177 => 65,  169 => 60,  140 => 55,  132 => 51,  128 => 49,  107 => 36,  61 => 13,  273 => 96,  269 => 94,  254 => 92,  243 => 88,  240 => 86,  238 => 85,  235 => 74,  230 => 82,  227 => 81,  224 => 71,  221 => 77,  219 => 76,  217 => 75,  208 => 68,  204 => 72,  179 => 69,  159 => 72,  143 => 56,  135 => 53,  119 => 42,  102 => 32,  71 => 17,  67 => 29,  63 => 23,  59 => 21,  87 => 25,  201 => 92,  196 => 90,  183 => 82,  171 => 61,  166 => 71,  163 => 62,  158 => 67,  156 => 66,  151 => 63,  142 => 72,  138 => 54,  136 => 56,  121 => 46,  117 => 44,  105 => 40,  91 => 27,  62 => 23,  49 => 19,  38 => 8,  28 => 3,  21 => 2,  25 => 4,  94 => 28,  89 => 20,  85 => 25,  75 => 17,  68 => 19,  56 => 17,  24 => 3,  31 => 4,  26 => 6,  19 => 1,  93 => 28,  88 => 6,  78 => 21,  46 => 7,  44 => 12,  27 => 4,  79 => 18,  72 => 26,  69 => 25,  47 => 9,  40 => 7,  37 => 10,  22 => 2,  246 => 90,  157 => 56,  145 => 46,  139 => 45,  131 => 52,  123 => 47,  120 => 40,  115 => 43,  111 => 37,  108 => 36,  101 => 32,  98 => 31,  96 => 31,  83 => 25,  74 => 14,  66 => 15,  55 => 15,  52 => 10,  50 => 10,  43 => 8,  41 => 9,  35 => 14,  32 => 13,  29 => 3,  209 => 82,  203 => 78,  199 => 67,  193 => 73,  189 => 71,  187 => 84,  182 => 66,  176 => 64,  173 => 65,  168 => 72,  164 => 59,  162 => 73,  154 => 58,  149 => 51,  147 => 58,  144 => 74,  141 => 48,  133 => 55,  130 => 41,  125 => 44,  122 => 43,  116 => 41,  112 => 42,  109 => 34,  106 => 36,  103 => 32,  99 => 31,  95 => 28,  92 => 21,  86 => 28,  82 => 22,  80 => 23,  73 => 19,  64 => 25,  60 => 21,  57 => 15,  54 => 10,  51 => 14,  48 => 8,  45 => 17,  42 => 7,  39 => 19,  36 => 17,  33 => 4,  30 => 7,);
    }
}
