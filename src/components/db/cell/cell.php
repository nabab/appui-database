<!-- HTML Document -->
<div :class="['appui-database-db-cell', 'bbn-spadding', 'bbn-w-100', 'bbn-nowrap', {'bbn-i': isOnlyVirtual}]">
  <a class="bbn-b"
     :href="link"
     bbn-text="source.name"/>
  <div :class="['bbn-vmiddle', 'bbn-top-xsspace', 'bbn-light', {
         'bbn-success-text': isRealVirtual,
         'bbn-secondary-text-alt': !isRealVirtual
       }]"
       style="gap: var(--sspace)">
    <div :class="['bbn-vmiddle', 'bbn-nowrap', {
           'bbn-error-text': isRealVirtual && (source.num_real_tables !== source.num_tables)
         }]"
         title="<?=_("Number of tables (real|options)")?>">
      <i class="nf nf-md-table bbn-right-xsspace"/>
      <span bbn-if="isRealVirtual || !isOnlyVirtual"
            bbn-text="source.num_real_tables || 0"/>
      <span bbn-if="isRealVirtual">|</span>
      <span bbn-if="isRealVirtual || isOnlyVirtual"
            bbn-text="source.num_tables || 0"/>
    </div>
    <div :class="['bbn-vmiddle', 'bbn-nowrap', {
           'bbn-error-text': isRealVirtual && (source.num_real_procedures !== source.num_procedures)
         }]"
         title="<?=_("Number of procedures (real|options)")?>">
      <i class="nf nf-md-alpha_p_circle_outline bbn-right-xsspace"/>
      <span bbn-if="isRealVirtual || !isOnlyVirtual"
            bbn-text="source.num_real_procedures || 0"/>
      <span bbn-if="isRealVirtual">|</span>
      <span bbn-if="isRealVirtual || isOnlyVirtual"
            bbn-text="source.num_procedures || 0"/>
    </div>
    <div :class="['bbn-vmiddle', 'bbn-nowrap', {
           'bbn-error-text': isRealVirtual && (source.num_real_functions !== source.num_functions)
         }]"
         title="<?=_("Number of functions (real|options)")?>">
      <i class="nf nf-md-function bbn-right-xsspace"/>
      <span bbn-if="isRealVirtual || !isOnlyVirtual"
            bbn-text="source.num_real_functions || 0"/>
      <span bbn-if="isRealVirtual">|</span>
      <span bbn-if="isRealVirtual || isOnlyVirtual"
            bbn-text="source.num_functions || 0"/>
    </div>
  </div>
</div>