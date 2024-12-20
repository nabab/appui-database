<!-- HTML Document -->
 <!--  :action="source.root +'actions/column/validform'" -->

<bbn-form
          ref="form"
          :source="source"
          :buttons="[]">
  <appui-database-column-form
                              ref="colform"
                              :source="source"
                              :otypes="otypes"
                              :engine="engine"
                              :host="host"
                              :db="db"
                              :predefined="predefined"
                              :table="table"
                              @change="submit"
                              @cancel="cancel"
                              >
  </appui-database-column-form>
</bbn-form>
