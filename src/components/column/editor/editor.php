<!-- HTML Document -->
 <!--  :action="source.root +'actions/column/validform'" -->

<div>
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
