<div class="bbn-overlay">
  <div v-if="!ready" class="bbn-overlay bbn-middle">
    <bbn-loader></bbn-loader>
  </div>
  <div class="bbn-overlay bbn-middle"
       v-else-if="currentData.error">
    <div class="bbn-block bbn-padded bbn-shadow bbn-state-error bbn-lg bbn-xlmargin"
         v-text="currentData.error"/>
  </div>
  <bbn-splitter v-else
                :orientation="orientation"
                :resizable="true"
                :collapsible="true">
    <bbn-pane :resizable="true"
              size="20%">
      <div class="bbn-100 bbn-lg">
        <div class="bbn-header bbn-spadded">
          <bbn-button icon="nf nf-mdi-export"
                      :notext="true"
                      @click="exportDb"/>
        </div>
        <div class="bbn-w-100 bbn-spadded bbn-c">
          <?=_("Engine")?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadded bbn-c"
             v-text="currentData.engine"/>

        <div class="bbn-w-100 bbn-m bbn-spadded bbn-c">
          <?=_("Host")?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadded bbn-c"
             v-text="currentData.host"/>

        <div class="bbn-w-100 bbn-spadded bbn-c"
             v-if="currentData.ip && (currentData.host !== currentData.ip)">
          <?=_("Ip")?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadded bbn-c"
             v-if="currentData.ip && (currentData.host !== currentData.ip)"
             v-text="currentData.ip"/>
      </div>
    </bbn-pane>
    <bbn-pane :resizable="true">
      <div class="bbn-overlay">
        <bbn-table ref="table"
                   @toggle="checkMultipleSelected"
                   :source="currentData.dbs.data"
                   :selection="true"
                   :pageable="true"
                   :limit="50"
                   :toolbar="toolbar"
                   :info="true"
                   uid="name"
                   >
          <bbns-column field="name"
                       title="<?=_('Database')?>"
                       component="appui-database-db-cell"/>
          <bbns-column title="<?=_("Action")?>"
                       :width="200"
                       cls="bbn-c"
                       :component="$options.components.dropdown"/>
        </bbn-table>
      </div>
    </bbn-pane>
  </bbn-splitter>
</div>