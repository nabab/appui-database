<!-- HTML Document -->
 <!--  :action="source.root +'actions/column/validform'" -->

<bbn-form
          ref="form"
          :source="source.source"
          :buttons="[]">
  <appui-database-column-form
                              ref="colform"
                              :source="source.source"
                              :otypes="source.types"
                              :engine="source.engine"
                              :host="source.host"
                              :db="source.db"
                              :predefined="source.predefined"
                              :table="source.table"
                              @change="submit"
                              @cancel="cancel"
                              >
  </appui-database-column-form>
</bbn-form>