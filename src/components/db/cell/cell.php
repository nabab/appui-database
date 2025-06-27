<!-- HTML Document -->
<div class="bbn-block bbn-spadding">
  <div class="bbn-w-100 bbn-nowrap">
    <a class="bbn-b"
       :href="link"
       bbn-text="source.name"/>
    <div class="bbn-vmiddle bbn-tertiary-text-alt bbn-top-xsspace bbn-light"
         style="gap: var(--sspace)">
      <div class="bbn-vmiddle bbn-nowrap"
           title="<?=_("Number of tables (real|options)")?>">
        <i class="nf nf-md-table bbn-right-xsspace"/>
        <span bbn-text="source.num_real_tables || 0"/>
        |
        <span bbn-text="source.num_tables || 0"/>
      </div>
      <div class="bbn-vmiddle bbn-nowrap"
           title="<?=_("Number of procedures (real|options)")?>">
        <i class="nf nf-md-alpha_p_circle_outline bbn-right-xsspace"/>
        <span bbn-text="source.num_real_procedures || 0"/>
        |
        <span bbn-text="source.num_procedures || 0"/>
      </div>
      <div class="bbn-vmiddle bbn-nowrap"
           title="<?=_("Number of functions (real|options)")?>">
        <i class="nf nf-md-function bbn-right-xsspace"/>
        <span bbn-text="source.num_real_functions || 0"/>
        |
        <span bbn-text="source.num_functions || 0"/>
      </div>
    </div>
  </div>
</div>