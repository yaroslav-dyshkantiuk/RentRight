<div class="wrapper filter-form">
    <?php $rentRight = new RentRight(); ?>
    <form action="<?php get_post_type_archive_link('property'); ?>" method="post">
        <select name="rentright_location">
            <option value="">Select Location</option>
            <?php echo $rentRight->get_terms_hierarchical('location', $_POST['rentright_location']); ?>
        </select>
        <select name="rentright_property-type">
            <option value="">Select Type</option>
            <?php echo $rentRight->get_terms_hierarchical('property-type', $_POST['rentright_property-type']); ?>
        </select>

        <input type="text" placeholder="Maximum Price" name="rentright_price" value="<?php  if(isset($_POST['rentright_price'])){echo esc_attr($_POST['rentright_price']);} ?>" />
        <select name="rentright_type">
            <option value="">Select Offer</option>
            <option value="sale" <?php if(isset($_POST['rentright_type']) and $_POST['rentright_type'] == 'sale') { echo 'selected'; } ?>>For Sale</option>
            <option value="rent" <?php if(isset($_POST['rentright_type']) and $_POST['rentright_type'] == 'rent') { echo 'selected'; } ?>>For Rent</option>
            <option value="sold"  <?php if(isset($_POST['rentright_type']) and $_POST['rentright_type'] == 'sold') { echo 'selected'; } ?>>Sold</option>
        </select>
        <select name="rentright_agent">
            <option value="">Select Agent</option>
            <?php
            $agents = get_posts(array('post_type'=>'agent','numberposts'=>-1));

            $selected = '';
            if(isset($_POST['rentright_agent'])){
                $agent_id = $_POST['rentright_agent'];
            }

            foreach($agents as $agent){
                echo '<option value="'.$agent->ID.'" '.selected($agent->ID, $agent_id,false).' >'.$agent->post_title.'</option>';
            }
            ?>
        </select>
        <input type="submit" name="submit" value="Filter">
    </form>
</div>