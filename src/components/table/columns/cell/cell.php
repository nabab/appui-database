<!-- HTML Document -->
<div :class="['appui-database-table-columns-cell', 'bbn-spadding', 'bbn-w-100', 'bbn-nowrap', {'bbn-i': isOnlyVirtual}]">
  <a class="bbn-b"
     :href="link"
     bbn-text="source.name"/>
  <div :class="['bbn-vmiddle', 'bbn-top-xsspace', 'bbn-light', {
         'bbn-success-text': isRealVirtual,
         'bbn-secondary-text-alt': !isRealVirtual
       }]"
       style="gap: var(--sspace)">
    <div :class="['bbn-vmiddle', 'bbn-nowrap', {
           'bbn-error-text': isRealVirtual && (source.num_real_columns !== source.num_columns)
         }]"
         title="<?=_("Number of columns (real|options)")?>">
      <i class="nf nf-fa-columns bbn-right-xsspace"/>
      <span bbn-if="isRealVirtual || !isOnlyVirtual"
            bbn-text="source.num_real_columns || 0"/>
      <span bbn-if="isRealVirtual">|</span>
      <span bbn-if="isRealVirtual || isOnlyVirtual"
            bbn-text="source.num_columns || 0"/>
    </div>
    <div :class="['bbn-vmiddle', 'bbn-nowrap', {
           'bbn-error-text': isRealVirtual && (source.num_real_keys !== source.num_keys)
         }]"
         title="<?=_("Number of keys (real|options)")?>">
      <i class="nf nf-md-key_chain_variant bbn-right-xsspace"/>
      <span bbn-if="isRealVirtual || !isOnlyVirtual"
            bbn-text="source.num_real_keys || 0"/>
      <span bbn-if="isRealVirtual">|</span>
      <span bbn-if="isRealVirtual || isOnlyVirtual"
            bbn-text="source.num_keys || 0"/>
    </div>
    <div :class="['bbn-vmiddle', 'bbn-nowrap', {
           'bbn-error-text': isRealVirtual && (source.num_real_constraints !== source.num_constraints)
         }]"
         title="<?=_("Number of constraints (real|options)")?>">
      <i class="nf nf-md-relation_many_to_many bbn-right-xsspace"/>
      <span bbn-if="isRealVirtual || !isOnlyVirtual"
            bbn-text="source.num_real_constraints || 0"/>
      <span bbn-if="isRealVirtual">|</span>
      <span bbn-if="isRealVirtual || isOnlyVirtual"
            bbn-text="source.num_constraints || 0"/>
    </div>
  </div>
</div>