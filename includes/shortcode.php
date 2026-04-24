<?php
/**
 * Shortcode to display student list: [danh_sach_sinh_vien]
 */

function sm_student_list_shortcode() {
    $args = array(
        'post_type'      => 'sinh_vien',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        ?>
        <div class="sm-student-table-container">
            <table class="sm-student-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>MSSV</th>
                        <th>Họ tên</th>
                        <th>Lớp/Chuyên ngành</th>
                        <th>Ngày sinh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    while ( $query->have_posts() ) : $query->the_post();
                        $post_id = get_the_ID();
                        $mssv = get_post_meta( $post_id, '_sm_mssv', true );
                        $class = get_post_meta( $post_id, '_sm_class', true );
                        $dob = get_post_meta( $post_id, '_sm_dob', true );

                        // Format date if needed
                        $formatted_dob = $dob ? date_i18n( get_option( 'date_format' ), strtotime( $dob ) ) : 'N/A';
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><strong><?php echo esc_html( $mssv ); ?></strong></td>
                            <td><?php the_title(); ?></td>
                            <td><span class="sm-badge sm-badge-<?php echo sanitize_title( $class ); ?>"><?php echo esc_html( $class ); ?></span></td>
                            <td><?php echo esc_html( $formatted_dob ); ?></td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo '<p class="sm-no-students">' . __( 'Không tìm thấy sinh viên nào trong hệ thống.', 'student-manager' ) . '</p>';
    }

    return ob_get_clean();
}
add_shortcode( 'danh_sach_sinh_vien', 'sm_student_list_shortcode' );
