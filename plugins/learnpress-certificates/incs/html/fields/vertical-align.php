<?php
$aligns = array(
    'top'      => __( 'Top', 'learnpress-certificates' ),
    'middle'    => __( 'Middle', 'learnpress-certificates' ),
    'bottom'     => __( 'Bottom', 'learnpress-certificates' )
);
?>
<select name="<?php echo $field['name'];?>">
    <?php foreach( $aligns as $name => $text ){?>
    <option value="<?php echo $name;?>" <?php selected( ! empty( $field['std'] ) && $field['std'] == $name ? 1 : 0, 1 );?>><?php echo $text;?></option>
    <?php }?>
</select>