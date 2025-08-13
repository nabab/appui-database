<div class="appui-database-column-editor">
  <appui-database-column-form ref="form"
                              :source="source"
                              :otypes="otypes"
                              :engine="engine"
                              :host="host"
                              :db="db"
                              :predefined="predefined"
                              :table="table"
                              @submit="onSubmit"
                              @cancel="onCancel"
                              :columns="columns"
                              :windowed="true"
                              :options="options"/>
</div>
