<?php
/**** 権限関係 ****/

//ユーザープロフィールに役割を追加
add_action( 'show_user_profile', 'show_extra_profile_fields' );
add_action( 'edit_user_profile', 'show_extra_profile_fields' );

function show_extra_profile_fields( $user ) { ?>
<table class="form-table">
<tr>
<th><label for="gp">権限</label></th>
<td>
<select name="gp" id="gp" >
<option>選択なし</option>
<option value="1" <?php selected( 1, get_the_author_meta( 'gp', $user->ID ) ); ?>>講師</option>
<option value="2" <?php selected( 2, get_the_author_meta( 'gp', $user->ID ) ); ?>>スタジオオーナー</option>
<option value="3" <?php selected( 3, get_the_author_meta( 'gp', $user->ID ) ); ?>>サポーター</option>
</select>
</td>
</tr>
</table>
<?php }

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );

function save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( $user_id, 'gp', $_POST['gp'] );
}