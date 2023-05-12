<!-- HTML Document -->
<div class="bbn-overlay appui-database-table" >
  <div v-if="!ready" class="bbn-overlay bbn-middle">
    <bbn-loader></bbn-loader>
  </div>
  <div class="bbn-overlay bbn-middle"
       v-else-if="currentData?.error">
    <div class="bbn-block bbn-padded bbn-shadow bbn-state-error bbn-lg bbn-xlmargin"
         v-text="currentData.error"/>
  </div>
  <bbn-router :nav="true"
              :autoload="false"
              v-else>
    <bbns-container component="appui-database-table-info"
                    url="info"
                    title="<?=_('Info')?>"
                    :source="currentData"
                    icon="nf nf-oct-info"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-columns"
                    url="columns"
                    title="<?=_('Columns')?>"
                    :source="currentData"
                    icon="nf nf-fa-columns"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-keys"
                    url="keys"
                    icon="nf nf-oct-key"
                    title="<?=_('Keys')?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-data"
                    url="data"
                    icon="nf nf-mdi-format_list_bulleted_type"
                    title="<?=_('Data')?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container v-if="currentData.externals.length"
                    component="appui-database-table-externals"
                    url="externals"
                    icon="nf nf-oct-link"
                    title="<?=_('Externals links')?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-history"
                    url="history"
                    icon="nf nf-mdi-clock_start"
                    title="<?=_('History')?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
  </bbn-router>
</div>
