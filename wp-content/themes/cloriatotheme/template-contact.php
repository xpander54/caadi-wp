<?php
/*
Template Name: Contact
*/
?>
<?php
$nameError='';
$emailError='';
$commentError='';
if(isset($_POST['submitted'])) {
	if(trim($_POST['contactName']) === '') {
		$nameError = 'Por favor escribe tu nombre.';
		$hasError = true;
	} else {
		$name = trim($_POST['contactName']);
	}
	if(trim($_POST['email']) === '')  {
		$emailError = 'Por favor escribe tu correo electrónico.';
		$hasError = true;
	} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
		$emailError = 'Has introducido un correo electrónico no válido.';
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}
	if(trim($_POST['comments']) === '') {
		$commentError = 'Por favor escribe tu mensaje.';
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['comments']));
		} else {
			$comments = trim($_POST['comments']);
		}
	}
	if(!isset($hasError)) {
		$emailTo = get_option('tz_email');
		if (!isset($emailTo) || ($emailTo == '') ){
			$emailTo = 'poligono@gmail.com';
		}
		$subject = 'Información de cursos Caadi por: '.$name;
		$body = "Nombre: $name \n\nCorreo: $email \n\nComentarios: $comments";
		$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}
} ?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="grid_24 content_wrapper">
  <div class="grid_16 alpha">
    <!--Start Content-->
    <div class="content">
      <?php //if (function_exists('inkthemes_breadcrumbs')) inkthemes_breadcrumbs(); ?>
      <h2>
        <?php the_title(); ?>
      </h2>
      <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
      <?php if(isset($emailSent) && $emailSent == true) { ?>
      <div class="thanks">
        <p>Gracias, tu correo se env&iacute;o correctamente.</p>
      </div>
      <?php } else { ?>
      <?php the_content(); ?>
      <?php if(isset($hasError) || isset($captchaError)) { ?>
      <p class="error">Lo sentimos, ha ocurrido un error.
      <p>
        <?php } ?>
      <form action="<?php the_permalink(); ?>" class="contactform" method="post" id="contactForm">
        <label for="contactName">Tu nombre <span class="required">(requerido)</span>:</label>
        <?php if($nameError != '') { ?>
        <span class="error"> <?php echo $nameError;?> </span>
        <?php } ?>
        <br/>
        <input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="required requiredField" />
        <br/>
        <label for="email">Tu correo <span class="required">(requerido)</span>:</label>
        <?php if($emailError != '') { ?>
        <span class="error"> <?php echo $emailError;?> </span>
        <?php } ?>
        <br/>
        <input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="required requiredField email" />
        <br/>
        <label>Curso que te interesa: </label>
        <input class="text" type="text" name="website"  value="<?php if(isset($_POST['website']))  echo $_POST['website'];?>"/>
        <br/>
        <label for="commentsText">Tu mensaje <span class="required">(requerido)</span>:</label>
        <?php if($commentError != '') { ?>
        <span class="error"> <?php echo $commentError;?> </span>
        <?php } ?>
        <br/>
        <textarea name="comments" id="commentsText" rows="20" cols="30" class="required requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?>
</textarea>
        <br/>
        <input  class="btnSubmit" type="submit" name="submit" value="Submit"/>
        <input type="hidden" name="submitted" id="submitted" value="true" />
        <?php } ?>
        <?php endwhile;?>
      </form>
    </div>
    <!--End Content-->
  </div>

</div>
<!--End Content Wrapper-->
<div class="clear"></div>
</div>
<!--End Container-->
<?php get_footer(); ?>
