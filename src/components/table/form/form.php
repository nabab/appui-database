<!-- HTML Document -->

<div class="bbn-padded" style="width: 50em; height: 20em" >
  <bbn-form
            ref="form"
            :action="root + 'actions/table/create'"
            :data="tableData"
            :buttons="formButtons">
    <div class="bbn-grid-fields bbn-padded bbn-lg">
      <label><?=_("Table's name")?></label>
      <div>
        <bbn-input class="bbn-padded" v-model="formData.name" :required="true"/>
      </div>

      <label><?=_("Comment")?></label>
      <div>
        <bbn-input class="bbn-padded" v-model="formData.comment"/>
      </div>
    </div>

    <div class="bbn-grid-fields bbn-padded ">
      <template v-for="(col, i) in formData.columns">
        <div>
          <span v-if="edited === i" />
          <span v-else >
            <span>
              <i class="nf nf-mdi-key_variant bbn-padded"
               style="zoom: 1.5"
                 v-if="constraint === true"/>
            </span>
            <bbn-button :text="_('Move up')"
                        icon="nf nf-fa-long_arrow_up"
                        :notext="true"
                        @click="moveUp(i)"
                        :disabled="(i === 0) || (numMovableColumns <= 1)"
                        />
            <bbn-button :text="_('Move down')"
                        icon="nf nf-fa-long_arrow_down"
                        :notext="true"
                        @click="moveDown(i)"
                        :disabled="(i === numMovableColumns - 1) || (numMovableColumns <= 1)"
                        class="bbn-hsmargin"/>
            <span v-text="col.name" class="bbn-b "/>
          </span>
        </div>
        <div v-if="edited === i">
          <appui-database-column-form :source="col"
                                      :otypes="source.types"
                                      :engine="source.engine"
                                      :host="source.host"
                                      :db="source.db"
                                      :predefined="source.predefined"
                                      :tables="source.tables"
                                      @cancel="onCancel"
                                      @change="edited = -1"
                                      >
          </appui-database-column-form>
        </div>
        <div v-else v-text="getColDescription(col)" class="bbn-pre"></div>
      </template>
    </div>



    <div class="bbn-vpadded">
      <bbn-button @click="addColumn()"
                  :disabled="edited !== -1"
                  >
        <?= _("Add a new column") ?>
      </bbn-button>
    </div>
  </bbn-form>
</div>