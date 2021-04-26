<!-- HTML Document -->
<div class="bbn-overlay appui-database-table">
  <bbn-router :nav="true"
              :autoload="false">
    <bbns-container component="appui-database-table-info"
                    url="info"
                    title="<?=_('Info')?>"
                    :source="source"
                    icon="nf nf-oct-info"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-columns"
                    url="columns"
                    title="<?=_('Columns')?>"
                    :source="source"
                    icon="nf nf-fa-columns"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-keys"
                    url="keys"
                    icon="nf nf-oct-key"
                    title="<?=_('Keys')?>"
                    :source="source"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-data"
                    url="data"
                    icon="nf nf-mdi-format_list_bulleted_type"
                    title="<?=_('Data')?>"
                    :source="source"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container v-if="source.externals.length"
                    component="appui-database-table-externals"
                    url="externals"
                    icon="nf nf-oct-link"
                    title="<?=_('Externals links')?>"
                    :source="source"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-history"
                    url="history"
                    icon="nf nf-mdi-clock_start"
                    title="<?=_('History')?>"
                    :source="source"
                    :pinned="true"
                    :menu="false"/>
  </bbn-router>
</div>
