
<div>
    <span <?php echo get_block_wrapper_attributes(array('class'=>'storagepress-reserve-button')) ?> onclick="storagepress_reserve_unit_<?php the_ID(); ?>_modal.showModal()">
        Reserve
    </span>

    <dialog id="storagepress_reserve_unit_<?php the_ID(); ?>_modal">
        <button onclick="storagepress_reserve_unit_<?php the_ID(); ?>_modal.close()" class="storagepress-reserve-modal-close-button">&times;</button>
        <h4>Reserve Storage Unit "<?php the_title() ?>"</h4>
    </dialog>
</div>