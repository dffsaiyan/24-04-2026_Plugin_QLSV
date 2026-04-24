<?php
/**
 * Register Custom Meta Boxes for Student
 */

function sm_add_student_meta_boxes() {
    add_meta_box(
        'student_details',
        __( 'Thông tin chi tiết sinh viên', 'student-manager' ),
        'sm_render_student_meta_box',
        'sinh_vien',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'sm_add_student_meta_boxes' );

function sm_render_student_meta_box( $post ) {
    // Add Nonce for security
    wp_nonce_field( 'save_student_details', 'student_details_nonce' );

    // Get current values
    $mssv = get_post_meta( $post->ID, '_sm_mssv', true );
    $class = get_post_meta( $post->ID, '_sm_class', true );
    $dob = get_post_meta( $post->ID, '_sm_dob', true );

    $classes = array(
        'CNTT' => 'Công nghệ thông tin',
        'Kinh tế' => 'Kinh tế',
        'Marketing' => 'Marketing'
    );

    ?>
    <div class="sm-meta-box-wrapper">
        <p>
            <label for="sm_mssv"><strong><?php _e( 'Mã số sinh viên (MSSV):', 'student-manager' ); ?></strong></label><br>
            <input type="text" id="sm_mssv" name="sm_mssv" value="<?php echo esc_attr( $mssv ); ?>" class="widefat" />
        </p>
        <p>
            <label for="sm_class"><strong><?php _e( 'Lớp/Chuyên ngành:', 'student-manager' ); ?></strong></label><br>
            <select id="sm_class" name="sm_class" class="widefat">
                <option value=""><?php _e( '-- Chọn lớp/chuyên ngành --', 'student-manager' ); ?></option>
                <?php foreach ( $classes as $key => $label ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $class, $key ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="sm_dob"><strong><?php _e( 'Ngày sinh:', 'student-manager' ); ?></strong></label><br>
            <input type="date" id="sm_dob" name="sm_dob" value="<?php echo esc_attr( $dob ); ?>" class="widefat" />
        </p>
    </div>
    <?php
}

function sm_save_student_meta_box_data( $post_id ) {
    // Security checks
    if ( ! isset( $_POST['student_details_nonce'] ) || ! wp_verify_nonce( $_POST['student_details_nonce'], 'save_student_details' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save MSSV
    if ( isset( $_POST['sm_mssv'] ) ) {
        update_post_meta( $post_id, '_sm_mssv', sanitize_text_field( $_POST['sm_mssv'] ) );
    }

    // Save Class
    if ( isset( $_POST['sm_class'] ) ) {
        update_post_meta( $post_id, '_sm_class', sanitize_text_field( $_POST['sm_class'] ) );
    }

    // Save DOB
    if ( isset( $_POST['sm_dob'] ) ) {
        update_post_meta( $post_id, '_sm_dob', sanitize_text_field( $_POST['sm_dob'] ) );
    }
}
add_action( 'save_post', 'sm_save_student_meta_box_data' );
