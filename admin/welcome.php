<h1><?php esc_html_e('Welcome to RentRight Plugin','rentright'); ?></h1>
<div class="content">
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php
            settings_fields('rentright_settings');
            do_settings_sections('rentright_settings');
            submit_button();
        ?>
    </form>
</div>