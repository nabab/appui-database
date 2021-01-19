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
      <bbns-container component="appui-databases-table-main-columns"
                      url="columns"
                      title="<?=_('Columns')?>"
                      :source="source"
                      icon="nf nf-fa-columns"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-databases-table-main-keys"
                      url="keys"
                      icon="nf nf-oct-key"
                      title="<?=_('Keys')?>"
                      :source="source"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-databases-table-main-data"
                      url="data"
                      icon="nf nf-mdi-format_list_bulleted_type"
                      title="<?=_('Data')?>"
                      :source="source"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-databases-table-main-externals"
                      url="externals"
                      icon="nf nf-oct-link"
                      title="<?=_('Externals links')?>"
                      :source="source"
                      :pinned="true"
                      :menu="false"
      ></bbns-container>
      <bbns-container component="appui-databases-table-main-history"
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
                     title: '<a title=\'<?=\bbn\str::escape_squotes(_('Position in the table'))?>\'>#</a>',
                     cls: 'bbn-c',
                     width: '30'
                   }, {
                     field: 'key',
                     title: '<i class=\'nf nf-fa-key\' title=\'<?=\bbn\str::escape_squotes(_('Are there keys on the column?'))?>\'></i>',
                     render: writeKeyInCol,
                     cls: 'bbn-c bbn-bg-black',
                     width: '20'
                   }, {
                     field: 'name',
                     render: writeColumn,
                     title: '<?=\bbn\str::escape_squotes(_('Columns'))?>',
                     cls: 'bbn-c'
                   }, {
                     field: 'type',
                     title: '<?=\bbn\str::escape_squotes(_('Type'))?>',
                     cls: 'bbn-c',
                     render: writeType,
                     width: '100'
                   }, {
                     field: 'maxlength',
                     title: '<?=\bbn\str::escape_squotes(_('Length'))?>',
                     cls: 'bbn-c',
                     width: '60'
                   }, {
                     field: 'null',
                     title: '<i class=\'nf nf-fa-ban\' title=\'<?=\bbn\str::escape_squotes(_('Can the field be null?'))?>\'></i>',
                     cls: 'bbn-c',
                     width: '30',
                     render: writeNull
                   }, {
                     field: 'default_value',
                     title: '<?=\bbn\str::escape_squotes(_('Default'))?>',
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
                     title: '<?=\bbn\str::escape_squotes(_('Keys'))?>',
                     render: writeKey,
                     cls: 'bbn-c'
                   }, {
                     field: 'columns',
                     render: writeColInKey,
                     title: '<?=\bbn\str::escape_squotes(_('Columns'))?>',
                     cls: 'bbn-c'
                   }, {
                     field: 'unique',
                     type: 'boolean',
                     title:  '<?=\bbn\str::escape_squotes(_('Unique'))?>',
                     cls: 'bbn-c',
                     width: '60'
                   }]
                 }"
        ></bbns-container-->