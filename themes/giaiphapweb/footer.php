<?php
/**
 * The template for displaying the footer.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

global $flatsome_opt;
?>

</main>

<footer id="footer" class="footer">
	<section class="footer__main">
		<div class="section__inner">
			<form class="footer__contact-form" action="/submit-form" method="post">
				<div class="contact-form__header">
					<h3 class="contact-form__title">We service all of Australia and are more than happy to help you with
						all your suiting needs. Get in touch!</h3>
					<p class="contact-form__sub-title">0708 245 789 | info@giaiphapweb.vn | 178 An Dương Vương - P.Thanh Hà - TP.Hội An - T.Quảng Nam</p>
				</div>
				<div class="contact-form__main">
					<div class="contact-form__group-top">
						<div class="contact-form__form-group">
							<label class="contact-form__label " for="name" required>First Name
								<span class="form-required">*</span>
							</label>
							<input type="text" id="first-name" name="first_name" placeholder="Enter your first name"
								required>
						</div>
						<div class="contact-form__form-group">
							<label for="email">Email
							<span class="form-required">*</span>
							</label>
							</label>
							<input type="email" id="email" name="email" placeholder="Enter your email" required>
						</div>
						<div class="contact-form__form-group">
							<label for="phone">Phone</label>
							<input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
						</div>
					</div>
					<div class="contact-form__group-bottom">
						<div class="contact-form__form-group">
							<label for="message">Message (Optional)</label>
							<textarea id="message" name="message" rows="4"
								placeholder="Enter your message (optional)"></textarea>
						</div>
					</div>
				</div>

				<div class="contact-form__footer"><button type="submit">Submit</button></div>
			</form>
		</div>
	</section>
	<section class="footer__bottom">
		<div class="section__inner">
			<div class="footer__copyright">
				<p class="copyright__text">Giaiphapweb © 2024 All Rights Reserved.
					<a href="javascript:void(0)">Privacy Policy</a>
				</p>
			</div>
		</div>

	</section>
</footer>

</div>
<?php wp_footer(); ?>
</body>

</html>