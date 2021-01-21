<div class="bbn-overlay">
  <bbn-splitter :orientation="orientation"
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
             v-text="source.engine"/>

        <div class="bbn-w-100 bbn-m bbn-spadded bbn-c">
          <?=_("Host")?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadded bbn-c"
             v-text="source.host"/>

        <div class="bbn-w-100 bbn-spadded bbn-c"
             v-if="source.ip && (source.host !== source.ip)">
          <?=_("Ip")?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadded bbn-c"
             v-if="source.ip && (source.host !== source.ip)"
             v-text="source.ip"/>
      </div>
    </bbn-pane>
    <bbn-pane :resizable="true">
      <div class="bbn-overlay">
        <bbn-table ref="table"
                   @toggle="checkMultipleSelected"
                   :source="source.dbs.data"
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