<!-- HTML Document -->

<div class="bbn-overlay appui-database-table-main bbn-flex-height">
  <bbn-toolbar>
    <bbn-menu name="bbn-menu"
              orientation="horizontal"
              direction="bottom right"
              :source="menu"
              class="inline"
    ></bbn-menu>
    <span class="bbn-iblock bbn-left-space"><?=_("Size")?>: </span>&nbsp;
    <span class="bbn-b bbn-iblock bbn-right-space" v-text="source.size"></span>
    <span class="bbn-iblock" v-if="source.is_real">#<?=_("Records")?>: </span>&nbsp;
    <span class="bbn-b bbn-iblock" v-text="format(source.count)" v-if="source.is_real"></span>
  </bbn-toolbar>
  <div class="bbn-flex-fill">
    <bbn-router :nav="true"
                :autoload="false"
    >
      <bbns-container component="appui-database-table-main-columns"
                      url="columns"
                      title="<?=_('Columns')?>"
                      :source="source"
                      icon="nf nf-fa-columns"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-database-table-main-keys"
                      url="keys"
                      icon="nf nf-oct-key"
                      title="<?=_('Keys')?>"
                      :source="source"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-database-table-main-data"
                      url="data"
                      icon="nf nf-mdi-format_list_bulleted_type"
                      title="<?=_('Data')?>"
                      :source="source"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-database-table-main-externals"
                      url="externals"
                      icon="nf nf-oct-link"
                      title="<?=_('Externals links')?>"
                      :source="source"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-database-table-main-history"
                      url="history"
                      icon="nf nf-mdi-clock_start"
                      title="<?=_('History')?>"
                      :source="source"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
    </bbn-router>
  </div>
</div>
<!--bbns-container url="columns"
                 title="<?=_("Columns")?>"
                 component="bbn-table"
                 :component-attributes="{
                   source: source.columns.data,
                   pageable: true,
                   sortable: true,
                   filterable: true,
                   info: true,
                   columns: [{
                     field: 'position',
                     title: '<a title=\'<?=\bbn\Str::escapeSquotes(_('Position in the table'))?>\'>#</a>',
                     cls: 'bbn-c',
                     width: '30'
                   }, {
                     field: 'key',
                     title: '<i class=\'nf nf-fa-key\' title=\'<?=\bbn\Str::escapeSquotes(_('Are there keys on the column?'))?>\'></i>',
                     render: writeKeyInCol,
                     cls: 'bbn-c bbn-bg-black',
                     width: '20'
                   }, {
                     field: 'name',
                     render: writeColumn,
                     title: '<?=\bbn\Str::escapeSquotes(_('Columns'))?>',
                     cls: 'bbn-c'
                   }, {
                     field: 'type',
                     title: '<?=\bbn\Str::escapeSquotes(_('Type'))?>',
                     cls: 'bbn-c',
                     render: writeType,
                     width: '100'
                   }, {
                     field: 'maxlength',
                     title: '<?=\bbn\Str::escapeSquotes(_('Length'))?>',
                     cls: 'bbn-c',
                     width: '60'
                   }, {
                     field: 'null',
                     title: '<i class=\'nf nf-fa-ban\' title=\'<?=\bbn\Str::escapeSquotes(_('Can the field be null?'))?>\'></i>',
                     cls: 'bbn-c',
                     width: '30',
                     render: writeNull
                   }, {
                     field: 'default_value',
                     title: '<?=\bbn\Str::escapeSquotes(_('Default'))?>',
                     template: writeDefault,
                     cls: 'bbn-c',
                     width: '80'
                   }]
                 }"
        ></bbns-container-->
        
        <!--bbns-container url="keys"
                 title="<?=_("Keys")?>"
                 component="bbn-table"
                 :component-attributes="{
                   source: source.keys.data,
                   pageable: true,
                   sortable: true,
                   filterable: true,
                   info: true,
                   columns: [{
                     field: 'name',
                     title: '<?=\bbn\Str::escapeSquotes(_('Keys'))?>',
                     render: writeKey,
                     cls: 'bbn-c'
                   }, {
                     field: 'columns',
                     render: writeColInKey,
                     title: '<?=\bbn\Str::escapeSquotes(_('Columns'))?>',
                     cls: 'bbn-c'
                   }, {
                     field: 'unique',
                     type: 'boolean',
                     title:  '<?=\bbn\Str::escapeSquotes(_('Unique'))?>',
                     cls: 'bbn-c',
                     width: '60'
                   }]
                 }"
        ></bbns-container-->