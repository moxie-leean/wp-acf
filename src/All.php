<?php namespace Leean\Acf;

/**
 * Class to provide helpers for getting a all ACF fields for an entity.
 */
class All
{
	/**
	 * Is the ACF plugin active.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return function_exists( 'get_field_object' );
	}

	/**
	 * Get all field values.
	 *
	 * @param int $target The target object.
	 * @return array
	 */
	private static function get_fields( $target = 0 ) {
		$data = [];

		$fields = get_field_objects( $target );

		if ( ! $fields ) {
			return $data;
		}

		foreach ( $fields as $field_name => $field ) {
			$data[ $field_name ] =
				apply_filters(
					'ln_acf_field',
					$field['value'],
					$target,
					$field
				);
		}

		return $data;
	}

	/**
	 * Get the field value for a post.
	 *
	 * @param int $post_id The target post's id. Or leave blank for he current post if in the loop.
	 * @return array
	 */
	public static function get_post_field( $post_id = 0 ) {
		return self::get_fields( $post_id );
	}

	/**
	 * Get the fields for a comment.
	 *
	 * @param int|\WP_Comment $comment The target comment's id or object.
	 * @return mixed
	 */
	public static function get_comment_field( $comment ) {
		return self::get_fields( is_a( $comment, 'WP_Comment' ) ? $comment : "comment_{$comment}" );
	}

	/**
	 * Get the fields for an attachment.
	 *
	 * @param int $attachment_id The target attachment's id.
	 * @return mixed
	 */
	public static function get_attachment_fields( $attachment_id ) {
		return self::get_fields( $attachment_id );
	}

	/**
	 * Get the fields for a taxonomy term.
	 *
	 * @param array|\WP_Term $taxonomy_term The target term's [taxonomy, $term_id] or term object.
	 * @return mixed
	 * @throws \Exception
	 */
	public static function get_taxonomy_fields( $taxonomy_term ) {
		if ( is_a( $taxonomy_term, 'WP_Term' ) ) {
			return self::get_fields( $taxonomy_term );
		} elseif ( is_array( $taxonomy_term ) && count( $taxonomy_term ) >= 2 ) {
			return self::get_fields( "{$taxonomy_term[0]}_{$taxonomy_term[1]}" );
		}
		throw new \Exception( '$taxonomy_term must be either a term object or an array of [$taxonomy, $term_id]' );
	}

	/**
	 * Get the fields for a user.
	 *
	 * @param int $user_id The target user's id.
	 * @return mixed
	 */
	public static function get_user_fields( $user_id ) {
		return self::get_fields( "user_{$user_id}" );
	}

	/**
	 * Get the fields for a widget.
	 *
	 * @param int $widget_id The target widget's id.
	 * @return mixed
	 */
	public static function get_widget_fields( $widget_id ) {
		return self::get_fields( "widget_{$widget_id}" );
	}

	/**
	 * Get the fields for an option.
	 *
	 * @return mixed
	 */
	public static function get_option_fields() {
		return self::get_fields( 'option' );
	}
}
