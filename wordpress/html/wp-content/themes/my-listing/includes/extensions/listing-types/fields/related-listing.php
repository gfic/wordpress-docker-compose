<?php

namespace MyListing\Ext\Listing_Types\Fields;

class RelatedListingField extends Field {

	public function field_props() {
		$this->props['type'] = 'related-listing';
		$this->props['listing_type'] = '';
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getRelationTypeField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}

	protected function getRelationTypeField() { ?>
		<div class="form-group full-width">
			<label>Related Listing Type</label>
			<div class="select-wrapper">
				<select v-model="field.listing_type">
					<?php foreach ( self::$store['listing-types'] as $listing_type ): ?>
						<option value="<?php echo $listing_type->post_name ?>"><?php echo $listing_type->post_title ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	<?php }
}
