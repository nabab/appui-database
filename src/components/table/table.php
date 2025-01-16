<!-- HTML Document -->
<div class="bbn-overlay appui-database-table" >
  <div bbn-if="!ready" class="bbn-overlay bbn-middle">
    <bbn-loader></bbn-loader>
  </div>
  <div class="bbn-overlay bbn-middle"
       bbn-else-if="currentData?.error">
    <div class="bbn-block bbn-padding bbn-shadow bbn-state-error bbn-lg bbn-xlmargin"
         bbn-text="currentData.error"/>
  </div>
  <bbn-router :nav="true"
              :autoload="false"
              bbn-else>
    <bbns-container component="appui-database-table-info"
                    url="info"
                    label="<?= _('Info') ?>"
                    :source="currentData"
                    icon="nf nf-oct-info"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-columns"
                    url="columns"
                    label="<?= _('Columns') ?>"
                    :source="currentData"
                    icon="nf nf-fa-columns"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-keys"
                    url="keys"
                    icon="nf nf-oct-key"
                    label="<?= _('Keys') ?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-data"
                    url="data"
                    icon="nf nf-md-format_list_bulleted_type"
                    label="<?= _('Data') ?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container bbn-if="currentData.externals.length"
                    component="appui-database-table-externals"
                    url="externals"
                    icon="nf nf-oct-link"
                    label="<?= _('Externals links') ?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
    <bbns-container component="appui-database-table-history"
                    url="history"
                    icon="nf nf-md-clock_start"
                    label="<?= _('History') ?>"
                    :source="currentData"
                    :pinned="true"
                    :menu="false"/>
  </bbn-router>
</div>
