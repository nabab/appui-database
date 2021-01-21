// Javascript Document
(() => {
  var operators = {
    string: {
      "contains": "Contient",
      "eq": "Est",
      "neq": "N’est pas",
      "startswith": "Commence par",
      "doesnotcontain": "Ne contient pas",
      "endswith": "Se termine par",
      "isnull": "Est nul",
      "isnotnull": "N’est pas nul",
      "isempty": "Est vide",
      "isnotempty": "N’est pas vide"
    },
    number: {
      "eq": "Est égal à",
      "neq": "N’est pas égal à",
      "gte": "Est supérieur ou égal à",
      "gt": "Est supérieur à",
      "lte": "Est inférieur ou égal à",
      "lt": "Est inférieur à",
      "isnull": "Est nul",
      "isnotnull": "N’est pas nul"
    },
    date: {
      "eq": "Est égal à",
      "neq": "N’est pas égal à",
      "gte": "Est postérieur ou égal à",
      "gt": "Est postérieur à",
      "lte": "Est antérieur ou égal à",
      "lt": "Est antérieur à",
      "isnull": "Est nul",
      "isnotnull": "N’est pas nul"
    },
    enums: {
      "eq": "Est égal à",
      "neq": "N’est pas égal à",
      "isnull": "Est nul",
      "isnotnull": "N’est pas nul"
    },
    boolean: {
      "istrue": "Est vrai",
      "isfalse": "Est faux"
    }
  };
  return {
    props: ['fields'],
    mounted: function(){
      //bbn.fn.log("FILTER FORM MOUNTED", this);
    },
    methods: {
      has_no_value(op){
        return (op === '') || (op === 'isnull') || (op === 'isnotnull') || (op === 'isempty') || (op === 'isnotempty') || (op === 'istrue') || (op === 'isfalse');
      },
      validate(){
        if ( this.column && this.operator && (this.has_no_value(this.operator) || this.value) ){
          var tmp = {
            column: this.column,
            operator: this.operator
          };
          if ( !this.has_no_value(this.operator) ){
            tmp.value = this.value;
          }
          this.$parent.conditions.push(tmp);
          this.value = '';
          this.$refs.column.widget.select(0);
          this.$refs.column.widget.trigger("change");
        }
        else{
          appui.alert("Valeur obligatoire, sinon vous pouvez choisir d'autres opérateurs si vous cherchez un élément nul");
        }
      },
      get_operator_type(field){
        switch ( field.type ){
          case 'int':
            // maxlength is a string!
            if ( field.maxlength == 1 ){
              return 'boolean';
            }
            if ( (field.maxlength == 10) && field.keys ){
              return 'enums';
            }
            return 'number';
          case 'float':
          case 'decimal':
            return 'number';
          case 'date':
            return 'date';
          case 'datetime':
            return 'date';
          case 'time':
            return 'date';
          case 'enum':
            return 'enums';
          default:
            return 'string';
        }
      },
    },
    data: function (){
      return {
        column: '',
        operator: '',
        value: '',
        has_group: false,
        has_condition: true,
        type: '',
        vueComponent: '',
        items: [],
        operators: [],
        cfg: {}
      };
    },
    computed: {
      no_value: function(){
        return this.has_no_value(this.operator);
      },
      columns: function(){
        var r = [];
        for ( var n in this.fields ){
          r.push(n);
        }
        return r;
      },
    },
    watch: {
      column: function(newVal){
        var vm = this,
            ds = [];
        vm.cfg = {};
        if ( vm.fields[newVal] ){
          var c = vm.fields[newVal],
              currentComponent = vm.vueComponent;
          vm.type = vm.get_operator_type(c);
          if ( operators[vm.type] ){
            for ( var n in operators[vm.type] ){
              if ( c.null || ( (n !== 'isnull') && (n !== 'isnotnull')) ){
                ds.push({
                  text: operators[vm.type][n],
                  value: n
                });
              }
            }
          }
        }
        vm.operators = ds;
        if ( !newVal ){
          vm.operator = '';
          vm.vueComponent = '';
          vm.type = '';
        }
        else{
          bbn.fn.log("TYPE!!", c);
          switch ( c.type ){
            case 'int':
              if ( !c.signed && (c.maxlength == 1) ){
                vm.vueComponent = 'radio';
              }
              else if ( c.maxlength == 10 ){
                vm.vueComponent = 'tree-input';
                vm.cfg.source = appui.plugins['appui-option'] + '/tree';
              }
              else{
                if ( !c.signed ){
                  vm.cfg.min = 0;
                }
                vm.cfg.max = 1;
                for ( var i = 0; i < c.maxlength; i++ ){
                  vm.cfg.max = vm.cfg.max * 10;
                }
                vm.cfg.max--;
                vm.vueComponent = 'numeric';
              }
              break;
            case 'float':
            case 'decimal':
              vm.vueComponent = 'numeric';
              var tmp = c.maxlength.split(","),
                  max = parseInt(tmp[0]) - parseInt(tmp[1]);
              vm.cfg.format = 'n' + tmp[1];
              if ( !c.signed ){
                vm.cfg.min = 0;
              }
              vm.cfg.max = 1;
              for ( var i = 0; i < max; i++ ){
                vm.cfg.max = vm.cfg.max * 10;
              }
              vm.cfg.max--;
              vm.vueComponent = 'numeric';
              break;
            case 'enum':
              var tmp = eval('[' + c.extra + ']');
              if ( bbn.fn.isArray(tmp) ){
                vm.cfg.dataSource = bbn.fn.map(tmp, (a) => {
                  return {
                    text: a,
                    value: a
                  };
                });
                vm.cfg.optionLabel = bbn._("Choose a value");
                vm.vueComponent = 'dropdown';
              }
              break;
            case 'date':
              vm.vueComponent = 'datepicker';
              break;
            case 'datetime':
              vm.vueComponent = 'datetimepicker';
              break;
            case 'time':
              vm.vueComponent = 'timepicker';
              break;
            default:
              vm.vueComponent = 'input';
              break;
          }
        }

        if ( currentComponent !== vm.vueComponent ){
          if ( vm.$refs.value && vm.$refs.value.widget ){
            vm.$refs.value.widget.destroy();
            var $ele = $(vm.$refs.value.$el);
            kendo.unbind($ele);
            $ele.prependTo($ele.closest(".bbn-db-value")).nextAll().remove();
          }
          vm.$nextTick(function(){
            vm.$refs.operator.widget.select(0);
            vm.$refs.operator.widget.trigger("change");
          });
        }
      }
    }
  }
})();