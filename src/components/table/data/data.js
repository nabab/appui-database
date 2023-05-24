(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        columns: this.getColumnsCfg()
      };
    },
    computed: {
    },
    methods:{
      getColumnWidth(column, type) {
        switch(type) {
          case 'date':
            column.width = 100;
            break;
          case 'datetime':
            column.width = 140;
            break;
          case 'binary':
            column.width = 60;
            break;
          default:
            column.minWidth = '10em';
            break;
        }
        return column;
      },
      typeIsNum(type) {
        return (type === 'int' || type === 'tinyint' || type === 'bigint' || type === 'smallint' || type == 'mediumint' || type === 'real' || type === 'double' || type === 'decimal' || type === 'float');
      },
      typeIsText(type) {
        return (type === 'text' || type === 'smalltext' || type === 'tinytext' || type === 'bigtext' || type == 'mediumtext');
      },
      getColumnEditor(fieldName, type, key) {
        if (key === 'MUL') {
          return this.getForeignKeyEditor(fieldName);
        }
        if (type === 'date') {
          return 'bbn-datepicker';
        }
        if (type === 'datetime') {
          return 'bbn-datetimepicker';
        }
        if (this.typeIsNum(type)) {
          return 'bbn-numeric';
        }
        if (this.typeIsText(type)) {
          return 'bbn-textarea';
        }
        if (type === 'json') {
          return 'bbn-json-editor';
        }
        if (type === 'enum' || type === 'set') {
          return 'bbn-dropdown';
        }
        if (type === 'binary' || type === 'varbinary') {
          return 'bbn-upload';
        }
        return '';
      },
      getComponent(component, type) {
        if (component) {
          return (component);
        }
        if (type === 'binary') {
          return "appui-database-data-binary";
        }
        return '';
      },
      addButtonsColumn() {
        return {
          'buttons': [
            {
              'action': "edit",
              'icon': "nf nf-fa-edit",
              'notext': true,
            },
            {
              'action': "delete",
              'icon': "nf nf-fa-times",
              'notext': true
            }
          ],
          'fixed': "right",
          'title': bbn._("Action"),
          'width': "100",
        };
      },
      getForeignKeyEditor(fieldName) {
        const constraints = this.source.constraints[fieldName];
        if (constraints.num <= 1000) {
          return 'bbn-dropdown';
        }
        return 'appui-database-data-browser';
      },
      getForeignKeyEditorData(column, fieldName) {
        column.options = {
          source: appui.plugins['appui-database'] + '/data/external-values',
          data: {
            host: this.source.host,
            db: this.source.db,
            engine: this.source.engine,
            table: this.source.table,
            column: fieldName
          }
        };
        return column;
      },
      getEnumOptions(value) {
        return {
          source: value.extra.split("','").map(a => {
            return {text: bbn.fn.correctCase(a), value: a};
          })
        };
      },
      getNumericOptions(value, isSigned) {
        if (value === 'tinyint') {
          return isSigned ?
            { 'min': -127, 'max': 127} :
          { 'min': 0, 'max': 256};
        }
        if (value === 'smallint') {
          return isSigned ?
            {'min': -32768, 'max': 32768} :
          {'min': 0, 'max': 65535};
        }
        if (value === 'mediumint') {
          return isSigned ?
            {'min': -8388608, 'max': 8388608} :
          {'min': 0, 'max': 16777215};
        }
        return {'min': MIN_SAFE_INTEGER, 'max': MAX_SAFE_INTEGER};
      },
      getColumnsCfg() {
        let columnsStructure = this.source.structure;
        let res = [];
        for (const [key, value] of Object.entries(columnsStructure.fields)) {
          let column = {
            "field": key,
            "text": key,
            "editor": this.getColumnEditor(key, value.type, value.key),
            "component": this.getComponent(value.component, value.type),
            "cls": 'bbn-c'
          };
          if (value.key === 'MUL') {
            column = this.getForeignKeyEditorData(column, key);
          }
          column = this.getColumnWidth(column, value.type);
          if (value.type === 'enum' || value.type === 'set') {
            column.options.source = this.getEnumOptions(value);
          }
          if (column.editor === 'bbn-numeric') {
            column.options = this.getNumericOptions(value.type);
          } else if (column.editor === '') {
            delete column.editor;
          }
          res.push(column);
        }
        res.push(this.addButtonsColumn());
        bbn.fn.log('columnStructure:', columnsStructure);
        bbn.fn.log('tablecfg:', this.source.tableCfg);
        bbn.fn.log('res:', res);
        return res;
      },
      see(row, col){
        if (this.source.constraints[col.field] && row[col.field]) {
          this.post(
            this.root + 'actions/show',
            bbn.fn.extend({id: row[col.field]}, this.source.constraints[col.field]),
            () => {
              bbn.fn.log("SEE TO DO!");
            }
          );
        }
      },
      edit(row, obj, idx){
        this.$refs.table.edit(bbn.fn.extend(row, {original: this.$refs.table.originalData[idx]}), bbn._('Edit data'), idx);
      },
      success(d){
        if (d.success) {
          appui.success(bbn._('Data successfully updated'));
        }
      },
      copy(row, col, idx){
        if (row[col.field]) {
          bbn.fn.copy(row[col.field]);
          appui.success(bbn._('UID copied'));
        }
        else {
          appui.error(bbn._('The field has value ' + (row[col.field] !== '') ? row[col.field] : '""'));
        }
      },
    }
  };
})();
