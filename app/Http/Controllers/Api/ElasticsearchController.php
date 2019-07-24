<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CsvRequest;
use App\Http\Requests\Api\SearchRequest;
use Elasticsearch\Endpoints\Snapshot\Status;
use Illuminate\Http\Request;

class ElasticsearchController extends Controller
{
    //结构化elastic聚类
    protected $elastic;

    //封装服务体
    public function __construct()
    {
        $this->elastic = app('es');
    }

    //search account name 相关性支持
    public function search(SearchRequest $request)
    {
        //DSL结构
       $params = [
           'index' => 'sitemap',
           'body' => [
               'query' => [
                   'match' => [
                       'account_name' => [
                           'query' => $request->search,
                           'operator' => 'and'
                       ]
                   ]
               ]
           ]
       ];

       //search api
       $data = $this->elastic->search($params);

       return $this->response->array($data)->setStatusCode(200);
    }

    //elastic 单个id show
    public function show(Request $request)
    {
        //DSL结构
        $params = [
            'index' => 'sitemap',
            'id' => $request->id
        ];

        //_doc api
        $company = $this->elastic->get($params);

        return $this->response->array($company);

    }

    public function csv(CsvRequest $request)
    {
        $request->file('csv')->store('csv');

        return $this->response->created();
    }

    public function indices()
    {
        $params = [
            'index' => ['sitemap']
        ];

        $data = $this->elastic->indices()->getSettings($params);

        return $this->response->array($data);
    }

}
