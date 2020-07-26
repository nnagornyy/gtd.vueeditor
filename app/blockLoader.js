const path = require('path');
const fs = require('fs');
const find = require('find');

console.log(path.join(__dirname));
var blocks = [];
//recFindByExt(path.join(__dirname),null, blocks);
recFindByExt(path.join(__dirname), null, blocks);
console.log(blocks);
function recFindByExt(base,files,result)
{
    files = files || fs.readdirSync(base)
    result = result || []

    files.forEach(
        function (file) {
            var newbase = path.join(base,file)
            if ( fs.statSync(newbase).isDirectory() && newbase.indexOf('node_modules') === -1)
            {
                result = recFindByExt(newbase,fs.readdirSync(newbase),result)
            }
            else
            {
                if (file.indexOf('.vue') > 0)
                {
                    let componentName = file.substr(0, file.lastIndexOf("."));
                    let pathStr = path.resolve(base, file);
                    let rPath = path.relative(path.join(__dirname, 'src'), pathStr);
                    rPath = rPath.substr(0, rPath.lastIndexOf("."))
                    if(!rPath.startsWith('./')){
                        rPath = './'+rPath;
                    }
                    let config = getConfig(base, file);
                    if(config.name){
                        result.push({
                            filePath: rPath,
                            componentName: componentName,
                            config: config
                        })
                    }
                }
            }
        }
    )
    return result
}

function getConfig(dir, fileName) {
    let config = {}
    fileName = fileName.substr(0, fileName.lastIndexOf("."))
    let files = fs.readdirSync(dir);
    files.forEach(function (file) {
        if(file === fileName+'.conf.json'){
            config = JSON.parse(fs.readFileSync(path.resolve(dir, fileName+'.conf.json')))
        }
    })
    return config;
}

export default blocks;