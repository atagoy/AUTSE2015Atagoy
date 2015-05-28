<?php 
//If the form is submitted
if(isset($_POST['btsend'])) {

	//Check to see if the honeypot captcha field was filled in
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError = 'Введите имя';
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = 'Введите E-mail';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = 'Неверный e-mail';
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['comments']) === '') {
			$commentError = 'Введите текст сообщения';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {
			$emailTo = 'markabucayon@gmail.com'; // please change this to your desire email address
			$subject = 'Contact Form Submission from '.$name;
			$body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
			$headers = 'From: '.$name.' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			mail($emailTo, $subject, $body, $headers);			

			$emailSent = true;
		}
}

?>

<?php 
	get_header(); 
?>

	<div class="contents clear"> 
		<?php 
            require_once 'includes/left-side.php';			
        ?>
        <div class="middle-content">
    	    <h2><?php the_title(); ?></h2>	
	        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php
                    if(is_page('about')){
                        ?>
                            <div class="about-me">
                                <?php the_content(); ?>
                            </div>
                        <?php
                    }elseif(is_page('contact')) {
                        ?>
                            <div class="contact clear">
                            	<?php 
									the_content(); 						
								?>          
								<?php 
                                    if(isset($emailSent) && $emailSent == true) { 
										?>
                                            <div class="thanks">
                                                <h1>Спасибо, <?=$name;?></h1>
                                                <p>Ваше сообщение отправлено. Мы свяжемся с вами совсем скоро.</p>
                                            </div>                
                                		<?php
                                    } 
								?>   
								
                                <div class="contact-fillupform clear">
                                    <form action="<?php the_permalink(); ?>" id="contactForm" method="post">	
                                        <ul>                                
                                            <li>
                                                <label for="contactName">Имя</label>
                                                <input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName']; ?>" class="requiredField" />
                                                <?php if($nameError != '') { ?>
                                                    <span><?=$nameError;?></span> 
                                                <?php } ?>
                                            </li>
                                            
                                            <li>
                                                <label for="email">E-mail</label>
                                                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="requiredField email" />
                                                <?php if($emailError != '') { ?>
                                                    <span><?=$emailError;?></span>
                                                <?php } ?>
                                            </li>
                                            
                                            <li>
                                                <label for="subject">Тема</label>
                                                <input type="text" name="subject" id="subject" value="<?php if(isset($_POST['subject']))  echo $_POST['subject'];?>" />                                    </li>
                                            
                                            <li>
                                                <label for="commentsText">Сообщение</label>
                                                <textarea name="comments" id="commentsText" rows="20" cols="30" class="requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
                                                <?php if($commentError != '') { ?>
                                                    <span><?=$commentError;?></span> 
                                                <?php } ?>
                                            </li>
                                            
                                            <li>
                                                <p><label>&nbsp;</label>        
                                                <input class="btn" type="submit" name="btsend" value="Отправить" id="btncontact" />
                                                <input class="btn" type="reset" name="btreset" value="Сбросить" id="btnreset" />
                                                </p>
                                            </li>                                 
                                        </ul>
                                    </form>                            
                                 </div>
							</div>
						<?php
                    }
                ?>
             <?php endwhile; endif; ?>
            </div> 

		<?php 
            get_sidebar();
        ?>    	
    </div>
</div>
    
<?php 
	get_footer(); 
?>
