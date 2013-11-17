<p>
    <label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title', PLUGIN_LOCALE ) ?></label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" value="<?php echo esc_attr( $title ) ?>"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'woeid' ) ?>"><?php _e( 'WOEID', PLUGIN_LOCALE ) ?></label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'woeid' ) ?>" name="<?php echo $this->get_field_name( 'woeid' ) ?>" value="<?php echo esc_attr( $woeid ) ?>"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'unit' ) ?>"><?php _e( 'Temperature unit', PLUGIN_LOCALE ) ?></label>
    <select class="widefat" name="<?php echo $this->get_field_name( 'unit' ) ?>" id="<?php echo $this->get_field_name( 'unit' ) ?>">
        <option value="c" <?php echo $unit == 'c' ? 'selected' : '' ?>><?php _e( 'Celcius', PLUGIN_LOCALE ) ?></option>
        <option value="f" <?php echo $unit == 'f' ? 'selected' : '' ?>><?php _e( 'Farenheit', PLUGIN_LOCALE ) ?></option>
        <option value="b" <?php echo $unit == 'b' ? 'selected' : '' ?>><?php _e( 'Both', PLUGIN_LOCALE ) ?></option>
    </select>
</p>
<pre><?php print_r($body) ?></pre>
<pre id="xmltest"><?php print_r($xml) ?></pre>