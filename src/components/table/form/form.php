<!-- HTML Document -->

<div class="appui-database-table-form bbn-padded bbn-c bbn-w-100">
  <bbn-form :source="formData"
            ref="form"
            :scrollable="false"
            :action="root + 'actions/table/create'"
            :data="tableData"
            :validation="() => formData.name && formData.columns.length && (edited === -1)"
            :confirm-message="_('Are you sure you are ready to create the table?')"
            @keydown.esc.stop.prevent
            @success="onCreate">
    <div class="bbn-flex-width">
      <div>
        <h3 class="bbn-c">
          <?=_("Table information")?>
        </h3>
        <div class="bbn-grid-fields bbn-nowrap">

          <label><?=_("Table's name")?></label>
          <div>
            <bbn-input class="bbn-padded"
                       v-model="formData.name"
                       :required="true"/>
          </div>

          <label><?=_("Comment")?></label>
          <div>
            <bbn-input class="bbn-padded"
                       v-model="formData.comment"/>
          </div>

          <div> </div>
          <div class="bbn-vpadded">
            <bbn-button @click="addColumn()"
                        :disabled="edited !== -1"
                        :text="_('Add a new column')"/>

          </div>

          <template v-for="(col, i) in formData.columns">
            <div class="bbn-nowrap bbn-s">
              <span class="bbn-right-smargin">
                <i class="nf nf-mdi-key_variant"
                   :style="{visibility: formData.columns[i].constraint ? 'visible' : 'hidden'}"/>
              </span>
              <bbn-button :text="_('Move up')"
                          icon="nf nf-fa-long_arrow_up"
                          :notext="true"
                          @click="moveUp(i)"
                          :disabled="(edited !== -1) || (i === 0) || (numMovableColumns <= 1)"/>
              <bbn-button :text="_('Move down')"
                          icon="nf nf-fa-long_arrow_down"
                          :notext="true"
                          @click="moveDown(i)"
                          :disabled="(edited !== -1) || (i === numMovableColumns - 1) || (numMovableColumns <= 1)"/>
              <bbn-button :text="_('Edit')"
                          icon="nf nf-fa-edit"
                          :notext="true"
                          @click="edited = i"
                          :disabled="edited !== -1"/>
              <bbn-button :text="_('Remove')"
                          icon="nf nf-fa-trash"
                          :notext="true"
                          @click="removeColumn(i)"
                          :disabled="edited !== -1"/>
            </div>
            <div v-html="getColDescription(col)"
                 class="bbn-s"/>
          </template>
        </div>
      </div>
      <div class="bbn-flex-fill">
        <div v-if="edited > -1">
          <appui-database-column-form :source="formData.columns[edited]"
                                      :otypes="source.types"
                                      :engine="source.engine"
                                      :host="source.host"
                                      :db="source.db"
                                      :predefined="source.predefined"
                                      :constraints="source.constraints"
                                      @cancel="onCancel"
                                      @change="onChange"/>
        </div>
      </div>
    </div>
  </bbn-form>
</div>