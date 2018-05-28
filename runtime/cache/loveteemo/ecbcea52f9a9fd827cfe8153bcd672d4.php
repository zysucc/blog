<?php
//000000000001a:6:{s:7:"artnums";i:22;s:14:"artcommentnums";i:2;s:11:"commentnums";i:2;s:8:"articles";a:4:{i:0;O:23:"app\index\model\Article":30:{s:13:" * connection";a:0:{}s:8:" * query";N;s:7:" * name";s:7:"Article";s:8:" * table";N;s:8:" * class";s:23:"app\index\model\Article";s:8:" * error";N;s:11:" * validate";N;s:5:" * pk";N;s:8:" * field";a:0:{}s:11:" * readonly";a:0:{}s:10:" * visible";a:0:{}s:9:" * hidden";a:0:{}s:9:" * append";a:0:{}s:7:" * data";a:19:{s:6:"art_id";i:18;s:9:"art_title";s:53:"Nginx/LVS/HAProxy负载均衡软件的优缺点详解";s:7:"art_img";s:32:"/uploads/20180207/1517983615.jpg";s:10:"art_remark";s:185:"Nginx/LVS/HAProxy是目前使用最广泛的三种负载均衡软件，本人都在多个项目中实施过，参考了一些资料，结合自己的一些使用经验，总结一下。";s:11:"art_keyword";s:12:"负载均衡";s:7:"art_pid";i:13;s:8:"art_down";i:0;s:8:"art_file";N;s:11:"art_addtime";i:1517983615;s:11:"art_content";s:13355:"<p><font color="#ff0000"><span style="line-height: 1;">&nbsp; &nbsp;&nbsp;</span><span style="line-height: 1;">&nbsp; &nbsp;&nbsp;</span>一般对负载均衡的使用是随着网站规模的提升根据不同的阶段来使用不同的技术。具体的应用需求还得具体分析，如果是中小型的Web应用，比如日PV小于1000万，用Nginx就完全可以了；如果机器不少，可以用DNS轮询，LVS所耗费的机器还是比较多的；大型网站或重要的服务，且服务器比较多时，可以考虑用LVS。</font></p><p><font color="#ff0000">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一种是通过硬件来进行进行，常见的硬件有比较昂贵的F5和Array等商用的负载均衡器，它的优点就是有专业的维护团队来对这些服务进行维护、缺点就是花销太大，所以对于规模较小的网络服务来说暂时还没有需要使用；另外一种就是类似于Nginx/LVS/HAProxy的基于<a href="http://www.ha97.com/category/linux">Linux</a>的开源免费的负载均衡软件，这些都是通过软件级别来实现，所以费用非常低廉。</font></p><p><font color="#ff0000">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目前关于网站架构一般比较合理流行的架构方案：Web前端采用Nginx/HAProxy+Keepalived作负载均衡器；后端采用<a href="http://www.ha97.com/tag/mysql">MySQL</a>数据库一主多从和读写分离，采用LVS+Keepalived的架构。当然要根据项目具体需求制定方案。下面说说各自的特点和适用场合。</font></p><h2>一、Nginx</h2><p>Nginx的优点是：</p><p>1、工作在网络的7层之上，可以针对http应用做一些分流的策略，比如针对域名、目录结构，它的正则规则比HAProxy更为强大和灵活，这也是它目前广泛流行的主要原因之一，Nginx单凭这点可利用的场合就远多于LVS了。2、Nginx对网络稳定性的依赖非常小，理论上能ping通就就能进行负载功能，这个也是它的优势之一；相反LVS对网络稳定性依赖比较大，这点本人深有体会；3、Nginx安装和配置比较简单，测试起来比较方便，它基本能把错误用日志打印出来。LVS的配置、测试就要花比较长的时间了，LVS对网络依赖比较大。3、可以承担高负载压力且稳定，在硬件不差的情况下一般能支撑几万次的并发量，负载度比LVS相对小些。4、Nginx可以通过端口检测到服务器内部的故障，比如根据服务器处理网页返回的状态码、超时等等，并且会把返回错误的请求重新提交到另一个节点，不过其中缺点就是不支持url来检测。比如用户正在上传一个文件，而处理该上传的节点刚好在上传过程中出现故障，Nginx会把上传切到另一台服务器重新处理，而LVS就直接断掉了，如果是上传一个很大的文件或者很重要的文件的话，用户可能会因此而不满。5、Nginx不仅仅是一款优秀的负载均衡器/反向代理软件，它同时也是功能强大的Web应用服务器。LNMP也是近几年非常流行的web架构，在高流量的环境中稳定性也很好。6、Nginx现在作为Web反向加速缓存越来越成熟了，速度比传统的<a href="http://www.ha97.com/tag/squid">Squid</a>服务器更快，可以考虑用其作为反向代理加速器。7、Nginx可作为中层反向代理使用，这一层面Nginx基本上无对手，唯一可以对比Nginx的就只有lighttpd了，不过lighttpd目前还没有做到Nginx完全的功能，配置也不那么清晰易读，社区资料也远远没Nginx活跃。8、Nginx也可作为静态网页和图片服务器，这方面的性能也无对手。还有Nginx社区非常活跃，第三方模块也很多。</p><p>淘宝的前端使用的Tengine就是基于nginx做的二次开发定制版。</p><p>Nginx常规的HTTP请求和响应流程图：</p><p><img src="http://www.ha97.com/wp-content/uploads/2014/07/nginx-300x191.jpg" alt="nginx"></p><p>&nbsp;</p><p>Nginx的缺点是：1、Nginx仅能支持http、<a href="http://www.ha97.com/tag/https">https</a>和Email协议，这样就在适用范围上面小些，这个是它的缺点。2、对后端服务器的健康检查，只支持通过端口来检测，不支持通过url来检测。不支持Session的直接保持，但能通过ip_hash来解决。</p><h2>二、LVS</h2><p>LVS：使用<a href="http://www.ha97.com/tag/linux">Linux</a>内核集群实现一个高性能、高可用的负载均衡服务器，它具有很好的可伸缩性（Scalability)、可靠性（Reliability)和可管理性（Manageability)。</p><p>LVS的优点是：1、抗负载能力强、是工作在网络4层之上仅作分发之用，没有流量的产生，这个特点也决定了它在负载均衡软件里的性能最强的，对内存和cpu资源消耗比较低。2、配置性比较低，这是一个缺点也是一个优点，因为没有可太多配置的东西，所以并不需要太多接触，大大减少了人为出错的几率。3、工作稳定，因为其本身抗负载能力很强，自身有完整的双机热备方案，如LVS+Keepalived，不过我们在项目实施中用得最多的还是LVS/DR+Keepalived。4、无流量，LVS只分发请求，而流量并不从它本身出去，这点保证了均衡器IO的性能不会收到大流量的影响。5、应用范围比较广，因为LVS工作在4层，所以它几乎可以对所有应用做负载均衡，包括http、数据库、在线聊天室等等。</p><p>LVS DR(Direct Routing)模式的网络流程图：</p><p><img src="http://www.ha97.com/wp-content/uploads/2014/07/lvs_dr-300x287.jpg" alt="lvs_dr"></p><p>LVS的缺点是：1、软件本身不支持正则表达式处理，不能做动静分离；而现在许多网站在这方面都有较强的需求，这个是Nginx/HAProxy+Keepalived的优势所在。2、如果是网站应用比较庞大的话，LVS/DR+Keepalived实施起来就比较复杂了，特别后面有Windows&nbsp;<a href="http://www.ha97.com/tag/server">Server</a>的机器的话，如果实施及配置还有维护过程就比较复杂了，相对而言，Nginx/HAProxy+Keepalived就简单多了。</p><h2>三、HAProxy</h2><p>HAProxy的特点是：1、HAProxy也是支持虚拟主机的。2、HAProxy的优点能够补充Nginx的一些缺点，比如支持Session的保持，Cookie的引导；同时支持通过获取指定的url来检测后端服务器的状态。3、HAProxy跟LVS类似，本身就只是一款负载均衡软件；单纯从效率上来讲HAProxy会比Nginx有更出色的负载均衡速度，在并发处理上也是优于Nginx的。4、HAProxy支持TCP协议的负载均衡转发，可以对MySQL读进行负载均衡，对后端的MySQL节点进行检测和负载均衡，大家可以用LVS+Keepalived对MySQL主从做负载均衡。5、HAProxy负载均衡策略非常多，HAProxy的负载均衡算法现在具体有如下8种：① roundrobin，表示简单的轮询，这个不多说，这个是负载均衡基本都具备的；② static-rr，表示根据权重，建议关注；③ leastconn，表示最少连接者先处理，建议关注；④ source，表示根据请求源IP，这个跟Nginx的IP_hash机制类似，我们用其作为解决session问题的一种方法，建议关注；⑤ ri，表示根据请求的URI；⑥ rl_param，表示根据请求的URl参数’balance url_param’ requires an URL parameter name；⑦ hdr(name)，表示根据HTTP请求头来锁定每一次HTTP请求；⑧ rdp-cookie(name)，表示根据据cookie(name)来锁定并哈希每一次TCP请求。</p><h2>四、总结</h2><p>Nginx和LVS对比的总结：1、Nginx工作在网络的7层，所以它可以针对http应用本身来做分流策略，比如针对域名、目录结构等，相比之下LVS并不具备这样的功能，所以Nginx单凭这点可利用的场合就远多于LVS了；但Nginx有用的这些功能使其可调整度要高于LVS，所以经常要去触碰触碰，触碰多了，人为出问题的几率也就会大。2、Nginx对网络稳定性的依赖较小，理论上只要ping得通，网页访问正常，Nginx就能连得通，这是Nginx的一大优势！Nginx同时还能区分内外网，如果是同时拥有内外网的节点，就相当于单机拥有了备份线路；LVS就比较依赖于网络环境，目前来看服务器在同一网段内并且LVS使用direct方式分流，效果较能得到保证。另外注意，LVS需要向托管商至少申请多一个ip来做Visual IP，貌似是不能用本身的IP来做VIP的。要做好LVS管理员，确实得跟进学习很多有关网络通信方面的知识，就不再是一个HTTP那么简单了。3、Nginx安装和配置比较简单，测试起来也很方便，因为它基本能把错误用日志打印出来。LVS的安装和配置、测试就要花比较长的时间了；LVS对网络依赖比较大，很多时候不能配置成功都是因为网络问题而不是配置问题，出了问题要解决也相应的会麻烦得多。4、Nginx也同样能承受很高负载且稳定，但负载度和稳定度差LVS还有几个等级：Nginx处理所有流量所以受限于机器IO和配置；本身的bug也还是难以避免的。5、Nginx可以检测到服务器内部的故障，比如根据服务器处理网页返回的状态码、超时等等，并且会把返回错误的请求重新提交到另一个节点。目前LVS中 ldirectd也能支持针对服务器内部的情况来监控，但LVS的原理使其不能重发请求。比如用户正在上传一个文件，而处理该上传的节点刚好在上传过程中出现故障，Nginx会把上传切到另一台服务器重新处理，而LVS就直接断掉了，如果是上传一个很大的文件或者很重要的文件的话，用户可能会因此而恼火。6、Nginx对请求的异步处理可以帮助节点服务器减轻负载，假如使用apache直接对外服务，那么出现很多的窄带链接时apache服务器将会占用大 量内存而不能释放，使用多一个Nginx做apache代理的话，这些窄带链接会被Nginx挡住，apache上就不会堆积过多的请求，这样就减少了相当多的资源占用。这点使用squid也有相同的作用，即使squid本身配置为不缓存，对apache还是有很大帮助的。7、Nginx能支持http、https和email（email的功能比较少用），LVS所支持的应用在这点上会比Nginx更多。在使用上，一般最前端所采取的策略应是LVS，也就是DNS的指向应为LVS均衡器，LVS的优点令它非常适合做这个任务。重要的ip地址，最好交由LVS托管，比如数据库的 ip、webservice服务器的ip等等，这些ip地址随着时间推移，使用面会越来越大，如果更换ip则故障会接踵而至。所以将这些重要ip交给 LVS托管是最为稳妥的，这样做的唯一缺点是需要的VIP数量会比较多。Nginx可作为LVS节点机器使用，一是可以利用Nginx的功能，二是可以利用Nginx的性能。当然这一层面也可以直接使用squid，squid的功能方面就比Nginx弱不少了，性能上也有所逊色于Nginx。Nginx也可作为中层代理使用，这一层面Nginx基本上无对手，唯一可以撼动Nginx的就只有lighttpd了，不过lighttpd目前还没有能做到 Nginx完全的功能，配置也不那么清晰易读。另外，中层代理的IP也是重要的，所以中层代理也拥有一个VIP和LVS是最完美的方案了。具体的应用还得具体分析，如果是比较小的网站（日PV小于1000万），用Nginx就完全可以了，如果机器也不少，可以用DNS轮询，LVS所耗费的机器还是比较多的；大型网站或者重要的服务，机器不发愁的时候，要多多考虑利用LVS。</p><p>现在对网络负载均衡的使用是随着网站规模的提升根据不同的阶段来使用不同的技术：</p><p>第一阶段：利用Nginx或HAProxy进行单点的负载均衡，这一阶段服务器规模刚脱离开单服务器、单数据库的模式，需要一定的负载均衡，但是仍然规模较小没有专业的维护团队来进行维护，也没有需要进行大规模的网站部署。这样利用Nginx或HAproxy就是第一选择，此时这些东西上手快， 配置容易，在七层之上利用HTTP协议就可以。这时是第一选择。</p><p>第二阶段：随着网络服务进一步扩大，这时单点的Nginx已经不能满足，这时使用LVS或者商用Array就是首要选择，Nginx此时就作为LVS或者Array的节点来使用，具体LVS或Array的是选择是根据公司规模和预算来选择，Array的应用交付功能非常强大，本人在某项目中使用过，性价比也远高于F5，商用首选！但是一般来说这阶段相关人才跟不上业务的提升，所以购买商业负载均衡已经成为了必经之路。</p><p></p><p>第三阶段：这时网络服务已经成为主流产品，此时随着公司知名度也进一步扩展，相关人才的能力以及数量也随之提升，这时无论从开发适合自身产品的定制，以及降低成本来讲开源的LVS，已经成为首选，这时LVS会成为主流。最终形成比较理想的基本架构为：Array/LVS — Nginx/Haproxy — Squid/Varnish — AppServer。</p><p><br></p>";s:8:"art_view";i:2;s:14:"art_collection";i:0;s:7:"art_hit";i:6;s:7:"art_url";s:29:"http://www.ha97.com/5646.html";s:12:"art_original";i:0;s:8:"art_from";s:6:"Win 10";s:10:"art_author";s:6:"赵雨";s:8:"art_city";s:14:"湖北武汉XX";s:16:"art_downloadnums";i:0;}s:9:" * change";a:0:{}s:7:" * auto";a:0:{}s:9:" * insert";a:0:{}s:9:" * update";a:0:{}s:21:" * autoWriteTimestamp";b:0;s:13:" * createTime";s:11:"create_time";s:13:" * updateTime";s:11:"update_time";s:13:" * dateFormat";s:11:"Y-m-d H:i:s";s:7:" * type";a:0:{}s:11:" * isUpdate";b:1;s:14:" * updateWhere";N;s:11:" * relation";N;s:16:" * failException";b:0;s:17:" * useGlobalScope";b:1;s:16:" * batchValidate";b:0;s:16:" * resultSetType";N;}i:1;O:23:"app\index\model\Article":30:{s:13:" * connection";a:0:{}s:8:" * query";N;s:7:" * name";s:7:"Article";s:8:" * table";N;s:8:" * class";s:23:"app\index\model\Article";s:8:" * error";N;s:11:" * validate";N;s:5:" * pk";N;s:8:" * field";a:0:{}s:11:" * readonly";a:0:{}s:10:" * visible";a:0:{}s:9:" * hidden";a:0:{}s:9:" * append";a:0:{}s:7:" * data";a:19:{s:6:"art_id";i:21;s:9:"art_title";s:50:"redis常见问题并且与memcached比较优缺点";s:7:"art_img";s:32:"/uploads/20180225/1519547394.jpg";s:10:"art_remark";s:50:"redis常见问题并且与memcached比较优缺点";s:11:"art_keyword";s:15:"redis-memcached";s:7:"art_pid";i:2;s:8:"art_down";i:0;s:8:"art_file";N;s:11:"art_addtime";i:1519525833;s:11:"art_content";s:5651:"<h1><font color="#008000">Redis与Memcached的区别</font></h1><p>&nbsp;如果简单地比较Redis与Memcached的区别，大多数都会得到以下观点：</p><p>1 Redis不仅仅支持简单的k/v类型的数据，同时还提供list，set，hash等数据结构的存储。</p><p>2 Redis支持数据的备份，即master-slave模式的数据备份。</p><p>3 Redis支持数据的持久化，可以将内存中的数据保持在磁盘中，重启的时候可以再次加载进行使用。</p><p>在Redis中，并不是所有的数据都一直存储在内存中的。这是和Memcached相比一个最大的区别（我个人是这么认为的）。</p><p>Redis 只会缓存所有的key的信息，如果Redis发现内存的使用量超过了某一个阀值，将触发swap的操作，Redis根据“swappability = age*log(size_in_memory)”计算出哪些key对应的value需要swap到磁盘。然后再将这些key对应的value持久化到磁 盘中，同时在内存中清除。这种特性使得Redis可以保持超过其机器本身内存大小的数据。当然，机器本身的内存必须要能够保持所有的key，毕竟这些数据 是不会进行swap操作的。</p><p>同时由于Redis将内存中的数据swap到磁盘中的时候，提供服务的主线程和进行swap操作的子线程会共享这部分内存，所以如果更新需要swap的数据，Redis将阻塞这个操作，直到子线程完成swap操作后才可以进行修改。可以参考使用Redis特有内存模型前后的情况对比：</p><p>VM&nbsp;off:&nbsp;300k&nbsp;keys,&nbsp;4096&nbsp;bytes&nbsp;values:&nbsp;1.3G&nbsp;used</p><p>VM&nbsp;on:&nbsp;300k&nbsp;keys,&nbsp;4096&nbsp;bytes&nbsp;values:&nbsp;73M&nbsp;used</p><p>VM&nbsp;off:&nbsp;1&nbsp;million&nbsp;keys,&nbsp;256&nbsp;bytes&nbsp;values:&nbsp;430.12M&nbsp;used</p><p>VM&nbsp;on:&nbsp;1&nbsp;million&nbsp;keys,&nbsp;256&nbsp;bytes&nbsp;values:&nbsp;160.09M&nbsp;used</p><p>VM&nbsp;on:&nbsp;1&nbsp;million&nbsp;keys,&nbsp;values&nbsp;as&nbsp;large&nbsp;as&nbsp;you&nbsp;want,&nbsp;still:&nbsp;160.09M&nbsp;used&nbsp;</p><p><br></p><p></p><p>当 从Redis中读取数据的时候，如果读取的key对应的value不在内存中，那么Redis就需要从swap文件中加载相应数据，然后再返回给请求方。 这里就存在一个I/O线程池的问题。在默认的情况下，Redis会出现阻塞，即完成所有的swap文件加载后才会相应。这种策略在客户端的数量较小，进行 批量操作的时候比较合适。但是如果将Redis应用在一个大型的网站应用程序中，这显然是无法满足大并发的情况的。所以Redis运行我们设置I/O线程 池的大小，对需要从swap文件中加载相应数据的读取请求进行并发操作，减少阻塞的时间。</p><p>redis、memcache、mongoDB 对比从以下几个维度，对redis、memcache、mongoDB 做了对比，欢迎拍砖</p><p>1、性能都比较高，性能对我们来说应该都不是瓶颈总体来讲，TPS方面redis和memcache差不多，要大于mongodb</p><p><br></p><p>2、操作的便利性</p><p>memcache数据结构单一</p><p>redis丰富一些，数据操作方面，redis更好一些，较少的网络IO次数</p><p>mongodb支持丰富的数据表达，索引，最类似关系型数据库，支持的查询语言非常丰富</p><p><br></p><p>3、内存空间的大小和数据量的大小</p><p>redis在2.0版本后增加了自己的VM特性，突破物理内存的限制；可以对key value设置过期时间（类似memcache）</p><p>memcache可以修改最大可用内存,采用LRU算法</p><p>mongoDB适合大数据量的存储，依赖操作系统VM做内存管理，吃内存也比较厉害，服务不要和别的服务在一起</p><p><br></p><p>4、可用性（单点问题）对于单点问题，</p><p>redis，依赖客户端来实现分布式读写；主从复制时，每次从节点重新连接主节点都要依赖整个快照,无增量复制，因性能和效率问题，所以单点问题比较复杂；不支持自动sharding,需要依赖程序设定一致hash 机制。一种替代方案是，不用redis本身的复制机制，采用自己做主动复制（多份存储），或者改成增量复制的方式（需要自己实现），一致性问题和性能的权衡</p><p>Memcache本身没有数据冗余机制，也没必要；对于故障预防，采用依赖成熟的hash或者环状的算法，解决单点故障引起的抖动问题。</p><p>mongoDB支持master-slave,replicaset（内部采用paxos选举算法，自动故障恢复）,auto sharding机制，对客户端屏蔽了故障转移和切分机制。</p><p><br></p><p>5、可靠性（持久化）对于数据持久化和数据恢复，</p><p>redis支持（快照、AOF）：依赖快照进行持久化，aof增强了可靠性的同时，对性能有所影响</p><p>memcache不支持，通常用在做缓存,提升性能；</p><p>MongoDB从1.8版本开始采用binlog方式支持持久化的可靠性</p><p>6、数据一致性（事务支持）</p><p>Memcache 在并发场景下，用cas保证一致性</p><p>redis事务支持比较弱，只能保证事务中的每个操作连续执行</p><p>mongoDB不支持事务</p><p><br></p><p>7、数据分析</p><p>mongoDB内置了数据分析的功能(mapreduce),其他不支持</p><p><br></p><p>8、应用场景</p><p>redis：数据量较小的更性能操作和运算上</p><p>memcache：用于在动态系统中减少数据库负载，提升性能;做缓存，提高性能（适合读多写少，对于数据量比较大，可以采用sharding）</p><p>MongoDB:主要解决海量数据的访问效率问题&nbsp;</p><p><br></p>";s:8:"art_view";i:2;s:14:"art_collection";i:0;s:7:"art_hit";i:6;s:7:"art_url";s:0:"";s:12:"art_original";i:1;s:8:"art_from";s:6:"Win 10";s:10:"art_author";s:6:"赵雨";s:8:"art_city";s:14:"湖北武汉XX";s:16:"art_downloadnums";i:0;}s:9:" * change";a:0:{}s:7:" * auto";a:0:{}s:9:" * insert";a:0:{}s:9:" * update";a:0:{}s:21:" * autoWriteTimestamp";b:0;s:13:" * createTime";s:11:"create_time";s:13:" * updateTime";s:11:"update_time";s:13:" * dateFormat";s:11:"Y-m-d H:i:s";s:7:" * type";a:0:{}s:11:" * isUpdate";b:1;s:14:" * updateWhere";N;s:11:" * relation";N;s:16:" * failException";b:0;s:17:" * useGlobalScope";b:1;s:16:" * batchValidate";b:0;s:16:" * resultSetType";N;}i:2;O:23:"app\index\model\Article":30:{s:13:" * connection";a:0:{}s:8:" * query";N;s:7:" * name";s:7:"Article";s:8:" * table";N;s:8:" * class";s:23:"app\index\model\Article";s:8:" * error";N;s:11:" * validate";N;s:5:" * pk";N;s:8:" * field";a:0:{}s:11:" * readonly";a:0:{}s:10:" * visible";a:0:{}s:9:" * hidden";a:0:{}s:9:" * append";a:0:{}s:7:" * data";a:19:{s:6:"art_id";i:22;s:9:"art_title";s:45:"Mysql优化神器explain的参数使用说明";s:7:"art_img";s:32:"/uploads/20180225/1519547426.jpg";s:10:"art_remark";s:48:"MYSQL性能参数解析神器explain使用分析";s:11:"art_keyword";s:7:"Explain";s:7:"art_pid";i:14;s:8:"art_down";i:0;s:8:"art_file";N;s:11:"art_addtime";i:1519541151;s:11:"art_content";s:20905:"<h1><font color="#008080">MySQL 性能优化神器 Explain 使用分析</font></h1><h2>简介</h2><p><font color="#ff0000">MySQL 提供了一个 EXPLAIN 命令, 它可以对&nbsp;SELECT&nbsp;语句进行分析, 并输出&nbsp;SELECT&nbsp;执行的详细信息, 以供开发人员针对性优化.EXPLAIN 命令用法十分简单, 在 SELECT 语句前加上 Explain 就可以了, 例如:</font></p><pre><code>EXPLAIN&nbsp;SELECT&nbsp;*&nbsp;from&nbsp;user_info&nbsp;WHERE&nbsp;id&nbsp;&lt;&nbsp;300;</code></pre><h2>准备</h2><p>为了接下来方便演示 EXPLAIN 的使用, 首先我们需要建立两个测试用的表, 并添加相应的数据:</p><pre><code>CREATE TABLE `user_info` (
  `id`   BIGINT(20)  NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL DEFAULT '',
  `age`  INT(11)              DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name_index` (`name`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8

INSERT INTO user_info (name, age) VALUES ('xys', 20);
INSERT INTO user_info (name, age) VALUES ('a', 21);
INSERT INTO user_info (name, age) VALUES ('b', 23);
INSERT INTO user_info (name, age) VALUES ('c', 50);
INSERT INTO user_info (name, age) VALUES ('d', 15);
INSERT INTO user_info (name, age) VALUES ('e', 20);
INSERT INTO user_info (name, age) VALUES ('f', 21);
INSERT INTO user_info (name, age) VALUES ('g', 23);
INSERT INTO user_info (name, age) VALUES ('h', 50);
INSERT INTO user_info (name, age) VALUES ('i', 15);</code></pre><pre><code>CREATE TABLE `order_info` (
  `id`           BIGINT(20)  NOT NULL AUTO_INCREMENT,
  `user_id`      BIGINT(20)           DEFAULT NULL,
  `product_name` VARCHAR(50) NOT NULL DEFAULT '',
  `productor`    VARCHAR(30)          DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_product_detail_index` (`user_id`, `product_name`, `productor`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8

INSERT INTO order_info (user_id, product_name, productor) VALUES (1, 'p1', 'WHH');
INSERT INTO order_info (user_id, product_name, productor) VALUES (1, 'p2', 'WL');
INSERT INTO order_info (user_id, product_name, productor) VALUES (1, 'p1', 'DX');
INSERT INTO order_info (user_id, product_name, productor) VALUES (2, 'p1', 'WHH');
INSERT INTO order_info (user_id, product_name, productor) VALUES (2, 'p5', 'WL');
INSERT INTO order_info (user_id, product_name, productor) VALUES (3, 'p3', 'MA');
INSERT INTO order_info (user_id, product_name, productor) VALUES (4, 'p1', 'WHH');
INSERT INTO order_info (user_id, product_name, productor) VALUES (6, 'p1', 'WHH');
INSERT INTO order_info (user_id, product_name, productor) VALUES (9, 'p8', 'TE');</code></pre><h2>EXPLAIN 输出格式</h2><p>EXPLAIN 命令的输出内容大致如下:</p><pre><code>mysql&gt; explain select * from user_info where id = 2\G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: const
possible_keys: PRIMARY
          key: PRIMARY
      key_len: 8
          ref: const
         rows: 1
     filtered: 100.00
        Extra: NULL
1 row in set, 1 warning (0.00 sec)</code></pre><p>各列的含义如下:</p><ul><li><font size="3" color="#ff0000">id: SELECT 查询的标识符. 每个 SELECT 都会自动分配一个唯一的标识符.</font></li><li><font size="3" color="#ff0000"><br></font></li><li><font color="#ff0000" size="3">select_type: SELECT 查询的类型.</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">table: 查询的是哪个表</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">partitions: 匹配的分区</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">type: join 类型</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">possible_keys: 此次查询中可能选用的索引</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">key: 此次查询中确切使用到的索引.</font></li><li><font color="#ff0000" size="3"><br></font></li></ul><ul><li><font color="#ff0000" size="3">ref: 哪个字段或常数与 key 一起被使用</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">rows: 显示此查询一共扫描了多少行. 这个是一个估计值.</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">filtered: 表示此查询条件所过滤的数据的百分比</font></li><li><font color="#ff0000" size="3"><br></font></li><li><font color="#ff0000" size="3">extra: 额外的信息</font></li></ul><p>接下来我们来重点看一下比较重要的几个字段.</p><h3>select_type</h3><p>select_type&nbsp;表示了查询的类型, 它的常用取值有:</p><ul><li><font color="#ff0000">SIMPLE, 表示此查询不包含 UNION 查询或子查询</font></li><li><font color="#ff0000">PRIMARY, 表示此查询是最外层的查询</font></li><li><font color="#ff0000">UNION, 表示此查询是 UNION 的第二或随后的查询</font></li><li><font color="#ff0000">DEPENDENT UNION, UNION 中的第二个或后面的查询语句, 取决于外面的查询</font></li><li><font color="#ff0000">UNION RESULT, UNION 的结果</font></li><li><font color="#ff0000">SUBQUERY, 子查询中的第一个 SELECT</font></li><li><font color="#ff0000">DEPENDENT SUBQUERY: 子查询中的第一个 SELECT, 取决于外面的查询. 即子查询依赖于外层查询的结果.</font></li></ul><p>最常见的查询类别应该是&nbsp;SIMPLE&nbsp;了, 比如当我们的查询没有子查询, 也没有 UNION 查询时, 那么通常就是&nbsp;SIMPLE&nbsp;类型, 例如:</p><pre><code>mysql&gt; explain select * from user_info where id = 2\G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: const
possible_keys: PRIMARY
          key: PRIMARY
      key_len: 8
          ref: const
         rows: 1
     filtered: 100.00
        Extra: NULL
1 row in set, 1 warning (0.00 sec)</code></pre><p>如果我们使用了 UNION 查询, 那么 EXPLAIN 输出 的结果类似如下:</p><pre><code>mysql&gt; EXPLAIN (SELECT * FROM user_info  WHERE id IN (1, 2, 3))
    -&gt; UNION
    -&gt; (SELECT * FROM user_info WHERE id IN (3, 4, 5));
+----+--------------+------------+------------+-------+---------------+---------+---------+------+------+----------+-----------------+
| id | select_type  | table      | partitions | type  | possible_keys | key     | key_len | ref  | rows | filtered | Extra           |
+----+--------------+------------+------------+-------+---------------+---------+---------+------+------+----------+-----------------+
|  1 | PRIMARY      | user_info  | NULL       | range | PRIMARY       | PRIMARY | 8       | NULL |    3 |   100.00 | Using where     |
|  2 | UNION        | user_info  | NULL       | range | PRIMARY       | PRIMARY | 8       | NULL |    3 |   100.00 | Using where     |
| NULL | UNION RESULT | &lt;union1,2&gt; | NULL       | ALL   | NULL          | NULL    | NULL    | NULL | NULL |     NULL | Using temporary |
+----+--------------+------------+------------+-------+---------------+---------+---------+------+------+----------+-----------------+
3 rows in set, 1 warning (0.00 sec)</code></pre><h3>table</h3><p><font color="#ff0000">表示查询涉及的表或衍生表</font></p><h3>type</h3><p><font color="#ff0000">type&nbsp;字段比较重要, 它提供了判断查询是否高效的重要依据依据. 通过&nbsp;type&nbsp;字段, 我们判断此次查询是&nbsp;全表扫描&nbsp;还是&nbsp;索引扫描&nbsp;等.</font></p><h4>type 常用类型</h4><p>type 常用的取值有:</p><ul><li><font color="#ff0000">system: 表中只有一条数据. 这个类型是特殊的&nbsp;const&nbsp;类型.</font></li><li><font color="#ff0000"><br></font></li><li><font color="#ff0000">const: 针对主键或唯一索引的等值查询扫描, 最多只返回一行数据. const 查询速度非常快, 因为它仅仅读取一次即可.例如下面的这个查询, 它使用了主键索引, 因此&nbsp;type&nbsp;就是&nbsp;const&nbsp;类型的.</font></li></ul><pre><code>mysql&gt; explain select * from user_info where id = 2\G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: const
possible_keys: PRIMARY
          key: PRIMARY
      key_len: 8
          ref: const
         rows: 1
     filtered: 100.00
        Extra: NULL
1 row in set, 1 warning (0.00 sec)</code></pre><ul><li><font color="#ff0000">eq_ref: 此类型通常出现在多表的 join 查询, 表示对于前表的每一个结果, 都只能匹配到后表的一行结果. 并且查询的比较操作通常是&nbsp;=, 查询效率较高.</font> 例如:</li></ul><pre><code>mysql&gt; EXPLAIN SELECT * FROM user_info, order_info WHERE user_info.id = order_info.user_id\G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: order_info
   partitions: NULL
         type: index
possible_keys: user_product_detail_index
          key: user_product_detail_index
      key_len: 314
          ref: NULL
         rows: 9
     filtered: 100.00
        Extra: Using where; Using index
*************************** 2. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: eq_ref
possible_keys: PRIMARY
          key: PRIMARY
      key_len: 8
          ref: test.order_info.user_id
         rows: 1
     filtered: 100.00
        Extra: NULL
2 rows in set, 1 warning (0.00 sec)</code></pre><ul><li><font color="#ff0000">ref: 此类型通常出现在多表的 join 查询, 针对于非唯一或非主键索引, 或者是使用了&nbsp;最左前缀&nbsp;规则索引的查询.&nbsp;例如下面这个例子中, 就使用到了&nbsp;ref&nbsp;类型的查询:</font></li></ul><pre><code>mysql&gt; EXPLAIN SELECT * FROM user_info, order_info WHERE user_info.id = order_info.user_id AND order_info.user_id = 5\G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: const
possible_keys: PRIMARY
          key: PRIMARY
      key_len: 8
          ref: const
         rows: 1
     filtered: 100.00
        Extra: NULL
*************************** 2. row ***************************
           id: 1
  select_type: SIMPLE
        table: order_info
   partitions: NULL
         type: ref
possible_keys: user_product_detail_index
          key: user_product_detail_index
      key_len: 9
          ref: const
         rows: 1
     filtered: 100.00
        Extra: Using index
2 rows in set, 1 warning (0.01 sec)</code></pre><ul><li><font color="#ff0000">range: 表示使用索引范围查询, 通过索引字段范围获取表中部分数据记录. 这个类型通常出现在 =, &lt;&gt;, &gt;, &gt;=, &lt;, &lt;=, IS NULL, &lt;=&gt;, BETWEEN, IN() 操作中.当&nbsp;type&nbsp;是&nbsp;range&nbsp;时, 那么 EXPLAIN 输出的&nbsp;ref&nbsp;字段为 NULL, 并且&nbsp;key_len&nbsp;字段是此次查询中使用到的索引的最长的那个.</font></li></ul><p>例如下面的例子就是一个范围查询:</p><pre><code>mysql&gt; EXPLAIN SELECT *
    -&gt;         FROM user_info
    -&gt;         WHERE id BETWEEN 2 AND 8 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: range
possible_keys: PRIMARY
          key: PRIMARY
      key_len: 8
          ref: NULL
         rows: 7
     filtered: 100.00
        Extra: Using where
1 row in set, 1 warning (0.00 sec)</code></pre><ul><li><font color="#ff0000">index: 表示全索引扫描(full index scan), 和 ALL 类型类似, 只不过 ALL 类型是全表扫描, 而 index 类型则仅仅扫描所有的索引, 而不扫描数据.index&nbsp;类型通常出现在: 所要查询的数据直接在索引树中就可以获取到, 而不需要扫描数据. 当是这种情况时, Extra 字段 会显示Using index.</font></li></ul><p>例如:</p><pre><code>mysql&gt; EXPLAIN SELECT name FROM  user_info \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: index
possible_keys: NULL
          key: name_index
      key_len: 152
          ref: NULL
         rows: 10
     filtered: 100.00
        Extra: Using index
1 row in set, 1 warning (0.00 sec)</code></pre><p>上面的例子中, 我们查询的 name 字段恰好是一个索引, 因此我们直接从索引中获取数据就可以满足查询的需求了, 而不需要查询表中的数据. 因此这样的情况下, type 的值是&nbsp;index, 并且 Extra 的值是&nbsp;Using index.</p><ul><li><font color="#ff0000">ALL: 表示全表扫描, 这个类型的查询是性能最差的查询之一. 通常来说, 我们的查询不应该出现 ALL 类型的查询, 因为这样的查询在数据量大的情况下, 对数据库的性能是巨大的灾难. 如一个查询是 ALL 类型查询, 那么一般来说可以对相应的字段添加索引来避免.下面是一个全表扫描的例子, 可以看到, 在全表扫描时, possible_keys 和 key 字段都是 NULL, 表示没有使用到索引, 并且 rows 十分巨大, 因此整个查询效率是十分低下的.</font></li></ul><pre><code>mysql&gt; EXPLAIN SELECT age FROM  user_info WHERE age = 20 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: user_info
   partitions: NULL
         type: ALL
possible_keys: NULL
          key: NULL
      key_len: NULL
          ref: NULL
         rows: 10
     filtered: 10.00
        Extra: Using where
1 row in set, 1 warning (0.00 sec)</code></pre><h4>type 类型的性能比较</h4><p><font color="#ff0000">通常来说, 不同的 type 类型的性能关系如下:ALL &lt; index &lt; range ~ index_merge &lt; ref &lt; eq_ref &lt; const &lt; systemALL&nbsp;类型因为是全表扫描, 因此在相同的查询条件下, 它是速度最慢的.而&nbsp;index&nbsp;类型的查询虽然不是全表扫描, 但是它扫描了所有的索引, 因此比 ALL 类型的稍快.后面的几种类型都是利用了索引来查询数据, 因此可以过滤部分或大部分数据, 因此查询效率就比较高了.</font></p><h3>possible_keys</h3><p><font color="#ff0000">possible_keys&nbsp;表示 MySQL 在查询时, 能够使用到的索引. 注意, 即使有些索引在&nbsp;possible_keys&nbsp;中出现, 但是并不表示此索引会真正地被 MySQL 使用到. MySQL 在查询时具体使用了哪些索引, 由&nbsp;key&nbsp;字段决定.</font></p><h3>key</h3><p><font color="#ff0000">此字段是 MySQL 在当前查询时所真正使用到的索引.</font></p><h3>key_len</h3><p>表示查询优化器使用了索引的字节数. 这个字段可以评估组合索引是否完全被使用, 或只有最左部分字段被使用到.key_len 的计算规则如下:</p><ul><li><font color="#ff0000">字符串char(n): n 字节长度varchar(n): 如果是 utf8 编码, 则是 3&nbsp;n + 2字节; 如果是 utf8mb4 编码, 则是 4&nbsp;n + 2 字节.</font></li><li><font color="#ff0000"><br></font></li><li><font color="#ff0000">数值类型:TINYINT: 1字节SMALLINT: 2字节MEDIUMINT: 3字节INT: 4字节BIGINT: 8字节</font></li><li><font color="#ff0000"><br></font></li><li><font color="#ff0000">时间类型DATE: 3字节TIMESTAMP: 4字节DATETIME: 8字节</font></li><li><font color="#ff0000"><br></font></li><li><font color="#ff0000">字段属性: NULL 属性 占用一个字节. 如果一个字段是 NOT NULL 的, 则没有此属性.</font></li><li><br></li></ul><p>我们来举两个简单的栗子:</p><pre><code>mysql&gt; EXPLAIN SELECT * FROM order_info WHERE user_id &lt; 3 AND product_name = 'p1' AND productor = 'WHH' \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: order_info
   partitions: NULL
         type: range
possible_keys: user_product_detail_index
          key: user_product_detail_index
      key_len: 9
          ref: NULL
         rows: 5
     filtered: 11.11
        Extra: Using where; Using index
1 row in set, 1 warning (0.00 sec)</code></pre><p>上面的例子是从表 order_info 中查询指定的内容, 而我们从此表的建表语句中可以知道, 表&nbsp;order_info&nbsp;有一个联合索引:</p><pre><code>KEY `user_product_detail_index` (`user_id`, `product_name`, `productor`)</code></pre><p>不过此查询语句&nbsp;WHERE user_id &lt; 3 AND product_name = 'p1' AND productor = 'WHH'&nbsp;中, 因为先进行 user_id 的范围查询, 而根据&nbsp;最左前缀匹配&nbsp;原则, 当遇到范围查询时, 就停止索引的匹配, 因此实际上我们使用到的索引的字段只有&nbsp;user_id, 因此在&nbsp;EXPLAIN中, 显示的 key_len 为 9. 因为 user_id 字段是 BIGINT, 占用 8 字节, 而 NULL 属性占用一个字节, 因此总共是 9 个字节. 若我们将user_id 字段改为&nbsp;BIGINT(20) NOT NULL DEFAULT '0', 则 key_length 应该是8.</p><p>上面因为&nbsp;最左前缀匹配&nbsp;原则, 我们的查询仅仅使用到了联合索引的&nbsp;user_id&nbsp;字段, 因此效率不算高.</p><p>接下来我们来看一下下一个例子:</p><pre><code>mysql&gt; EXPLAIN SELECT * FROM order_info WHERE user_id = 1 AND product_name = 'p1' \G;
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: order_info
   partitions: NULL
         type: ref
possible_keys: user_product_detail_index
          key: user_product_detail_index
      key_len: 161
          ref: const,const
         rows: 2
     filtered: 100.00
        Extra: Using index
1 row in set, 1 warning (0.00 sec)</code></pre><p>这次的查询中, 我们没有使用到范围查询, key_len 的值为 161. 为什么呢? 因为我们的查询条件&nbsp;WHERE user_id = 1 AND product_name = 'p1'&nbsp;中, 仅仅使用到了联合索引中的前两个字段, 因此&nbsp;keyLen(user_id) + keyLen(product_name) = 9 + 50 * 3 + 2 = 161</p><h3>rows</h3><p><font color="#ff0000">rows 也是一个重要的字段. MySQL 查询优化器根据统计信息, 估算 SQL 要查找到结果集需要扫描读取的数据行数.这个值非常直观显示 SQL 的效率好坏, 原则上 rows 越少越好</font>.</p><h3>Extra</h3><p>EXplain 中的很多额外的信息会在 Extra 字段显示, 常见的有以下几种内容:</p><ul><li><font color="#ff0000">Using filesort当 Extra 中有&nbsp;Using filesort&nbsp;时, 表示 MySQL 需额外的排序操作, 不能通过索引顺序达到排序效果. 一般有&nbsp;Using filesort, 都建议优化去掉, 因为这样的查询 CPU 资源消耗大.</font></li></ul><p>例如下面的例子:</p><pre><code>mysql&gt; EXPLAIN SELECT * FROM order_info ORDER BY product_name \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: order_info
   partitions: NULL
         type: index
possible_keys: NULL
          key: user_product_detail_index
      key_len: 253
          ref: NULL
         rows: 9
     filtered: 100.00
        Extra: Using index; Using filesort
1 row in set, 1 warning (0.00 sec)</code></pre><p>我们的索引是</p><pre><code>KEY `user_product_detail_index` (`user_id`, `product_name`, `productor`)</code></pre><p>但是上面的查询中根据&nbsp;product_name&nbsp;来排序, 因此不能使用索引进行优化, 进而会产生&nbsp;Using filesort.如果我们将排序依据改为&nbsp;ORDER BY user_id, product_name, 那么就不会出现&nbsp;Using filesort&nbsp;了. 例如:</p><pre><code>mysql&gt; EXPLAIN SELECT * FROM order_info ORDER BY user_id, product_name \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: order_info
   partitions: NULL
         type: index
possible_keys: NULL
          key: user_product_detail_index
      key_len: 253
          ref: NULL
         rows: 9
     filtered: 100.00
        Extra: Using index
1 row in set, 1 warning (0.00 sec)</code></pre><p></p><ul><li><font style="background-color: rgb(255, 255, 255);" color="#ff0000">Using index"覆盖索引扫描", 表示查询在索引树中就可查找所需数据, 不用扫描表数据文件, 往往说明性能不错</font></li><li><font style="background-color: rgb(255, 255, 255);" color="#ff0000">Using temporary查询有使用临时表, 一般出现于排序, 分组和多表 join 的情况, 查询效率不高, 建议优化.</font></li></ul><p><br></p>";s:8:"art_view";i:2;s:14:"art_collection";i:0;s:7:"art_hit";i:5;s:7:"art_url";s:43:"https://segmentfault.com/a/1190000008131735";s:12:"art_original";i:0;s:8:"art_from";s:6:"Win 10";s:10:"art_author";s:6:"赵雨";s:8:"art_city";s:14:"湖北武汉XX";s:16:"art_downloadnums";i:0;}s:9:" * change";a:0:{}s:7:" * auto";a:0:{}s:9:" * insert";a:0:{}s:9:" * update";a:0:{}s:21:" * autoWriteTimestamp";b:0;s:13:" * createTime";s:11:"create_time";s:13:" * updateTime";s:11:"update_time";s:13:" * dateFormat";s:11:"Y-m-d H:i:s";s:7:" * type";a:0:{}s:11:" * isUpdate";b:1;s:14:" * updateWhere";N;s:11:" * relation";N;s:16:" * failException";b:0;s:17:" * useGlobalScope";b:1;s:16:" * batchValidate";b:0;s:16:" * resultSetType";N;}i:3;O:23:"app\index\model\Article":30:{s:13:" * connection";a:0:{}s:8:" * query";N;s:7:" * name";s:7:"Article";s:8:" * table";N;s:8:" * class";s:23:"app\index\model\Article";s:8:" * error";N;s:11:" * validate";N;s:5:" * pk";N;s:8:" * field";a:0:{}s:11:" * readonly";a:0:{}s:10:" * visible";a:0:{}s:9:" * hidden";a:0:{}s:9:" * append";a:0:{}s:7:" * data";a:19:{s:6:"art_id";i:23;s:9:"art_title";s:36:"Nginx与PHP的交互原理--fast_cgi";s:7:"art_img";s:32:"/uploads/20180225/1519547192.jpg";s:10:"art_remark";s:44:"Nginx与PHP的交互原理--fast_cgi,php-fpm";s:11:"art_keyword";s:8:"fast_cgi";s:7:"art_pid";i:1;s:8:"art_down";i:0;s:8:"art_file";N;s:11:"art_addtime";i:1519547102;s:11:"art_content";s:9413:"<p><span style="color: inherit; font-family: inherit; font-size: 38.5px; font-weight: bold;">你确定你真的懂Nginx与PHP的交互？&nbsp;原创</span></p><p>Nginx是俄国人最早开发的Webserver，现在已经风靡全球，相信大家并不陌生。PHP也通过二十多年的发展来到了7系列版本，更加关注性能。这对搭档在最近这些年，叱咤风云，基本上LNMP成了当下的标配。可是，你用了这么多年的Nginx+PHP的搭配，你真正知道他们之间是怎么交互怎么通信的么？作为一道常常用来面试的考题，从过往经验看，情况并不乐观。更多的同学是知道PHP-FPM、知道FastCGI，但不晓得Nginx、PHP这对老搭档具体的交互细节。那么，今天我们就来一起学习一下，做一回认真的PHP工程师。</p><p><strong>前菜</strong></p><p>为了讲解的有理有据，我们先来准备一个纯净精简的Nginx+PHP环境，这里我们使用Docker拉取Centos最新版本环境，来快速通过编译安装方式搭建一个Nginx+PHP环境。（图1，通过docker启动一台CentOS机器并进入）</p><p><img src="https://img.mukewang.com/596e558e0001c89b04520054.png" alt="图1，通过docker启动一台CentOS机器并进入"></p><p>有了Linux环境，我们来源码编译安装Nginx、PHP，这个过程网络里有很多的教程，我们就不细说了。当然你也可以安装lnmp一键安装包来快速搭建。通过安装nginx、php，我们的Linux环境里就有了今天的这两位主角了。我们稍加配置，让Nginx可以接收请求并转发给PHP-FPM，我们目标是输出一个phpinfo()的信息。（图2，phpinfo()的输出内容）<img src="https://img.mukewang.com/596e55bd00019daf18940871.png" alt="图2"></p><p>我们通过对Nginx新增Server配置实现了nginx与PHP的一次通信，配置文件非常简单，如下图：（图3，一份nginx server配置）<img src="https://img.mukewang.com/596e56010001222706100396.png" alt="图3，一份nginx"></p><p>有了上面的一个sample示例，我们开始深入Nginx与FastCGI协议。</p><p><strong>主食</strong></p><p>从上图的Nginx配置中可以注意到 fastcgi* 开头的一些配置，以及引入的 fastcgi.conf 文件。其实在fastcgi.conf中，也是一堆fastcgi*的配置项，只是这些配置项相对不常变，通常单独文件保管可以在多处引用。（图4，fastcgi.conf文件中的内容）<img src="https://img.mukewang.com/596e56510001032007890706.png" alt="图4，fastcgi.conf文件中的内容"></p><p>可以看到在fastcgi.conf中，有很多的fastcgi_param配置，结合nginx server配置中的fastcgi_pass、fastcgi_index，通常我们的同学已经能够想到Nginx与PHP之间打交道就是用的FastCGI，但再深问FastCGI是什么？它起到衔接Nginx、PHP的什么作用？等等深入的问题的时候，很多同学就卡壳了。那么，我们就来一探究竟。</p><p>CGI是通用网关协议，FastCGI则是一种常住进程的CGI模式程序。我们所熟知的PHP-FPM的全称是PHP FastCGI Process Manager，即PHP-FPM会通过用户配置来管理一批FastCGI进程，例如在PHP-FPM管理下的某个FastCGI进程挂了，PHP-FPM会根据用户配置来看是否要重启补全，PHP-FPM更像是管理器，而真正衔接Nginx与PHP的则是FastCGI进程。（图5，FastCGI在请求流中的位置）<img src="https://img.mukewang.com/596e56910001ae5d05590315.png" alt="图5，FastCGI在请求流中的位置"></p><p>如上图所示，FastCGI的下游，是CGI-APP，在我们的LNMP架构里，这个CGI-APP就是PHP程序。而FastCGI的上游是Nginx，他们之间有一个通信载体，即图中的socket。在我们上文图3的配置文件中，fastcgi_pass所配置的内容，便是告诉Nginx你接收到用户请求以后，你该往哪里转发，在我们图3中是转发到本机的一个socket文件，这里fastcgi_pass也常配置为一个http接口地址（这个可以在php-fpm.conf中配置）。而上图5中的Pre-fork，则对应着我们PHP-FPM的启动，也就是在我们启动PHP-FPM时便会根据用户配置启动诸多FastCGI触发器（FastCGI Wrapper）。</p><p>对FastCGI在Nginx+PHP的模式中的定位有了一定了解后，我们再来了解下Nginx中为何能写很多fastcgi_*的配置项。这是因为Nginx的一个默认内置module实现了FastCGI的Client。关于Module&nbsp;ngx_http_fastcgi_module的详细文档可以查看这里:<a href="http://nginx.org/en/docs/http/ngx_http_fastcgi_module.html" target="_blank"></a><a href="http://nginx.org/en/docs/http/ngx_http_fastcgi_module.html" target="_blank">http://nginx.org/en/docs/http/ngx_http_fastcgi_module.html</a>&nbsp;。我们关心一下我们图4中的这些fastcgi_param都是些什么吧，详细描述见下图。（图6，nginx模块中fastcgi_param的介绍）<img src="https://img.mukewang.com/596e56eb0001f80908140678.png" alt="图6，nginx模块中fastcgi_param的介绍"></p><p>从图6中可以看到，fastcgi_param所声明的内容，将会被传递给“FastCGI server”，那这里指的就是fastcgi_pass所指向的server，也就是我们Nginx+PHP模式下的PHP-FPM所管理的FastCGI进程，或者说是那个socket文件载体。这时，有的同学会问：“为什么PHP-FPM管理的那些FastCGI进程要关心这些参数呢？”，好问题，我们一起想想我们做PHP应用开发时候有没有用到 $_SERVER 这个全局变量，它里面包含了很多服务器的信息，比如包含了用户的IP地址。同学们不想想我们的PHP身处socket文件之后，为什么能得到远端用户的IP呢？聪明的同学应该注意到图4中的一个fastcgi_param配置 REMOTE_ADDR ，这不正是我们在PHP中用 $_SERVER[‘REMOTE_ADDR’] 取到的用户IP么。的确，Nginx这个模块里fastcgi_param参数，就是考虑后端程序有时需要获取Webserver外部的变量以及服务器情况，那么ngx_http_fastcgi_module就帮我们做了这件事。真的是太感谢它啦！</p><p>那么我们已经说清了FastCGI是个什么东东，并且它在Nginx+PHP中的定位。我们回到前面提出的问题，“它起到衔接Nginx、PHP的什么作用？”。</p><p>对PHP有一定了解的同学，应该会知道PHP提供SAPI面向Webserver来提供扩展编程。但是这样的方式意味着你要是自主研发一套Webserver，你就需要学习SAPI，并且在你的Webserver程序中实现它。这意味着你的Webserver与PHP产生了耦合。在互联网的大趋势下，一般大家都不喜欢看到耦合。譬如Nginx在最初研发时候也不是为了和PHP组成黄金搭档而研发的，相信早些年的Nginx后端程序可能是其他语言开发。那么解决耦合的办法，比较好的方式是有一套通用的规范，上下游都兼容它。那么CGI协议便成了Nginx、PHP都愿意接受的一种方式，而FastCGI常住进程的模式又让上下游程序有了高并发的可能。那么，FastCGI的作用是Nginx、PHP的接口载体，就像插座与插销，让流行的WebServer与“世界上最好的语言”有了合作的可能。</p><p>有了这些基础背景知识与他们的缘由，我们就可以举一反三的做更多有意思的事情。譬如我在前年曾实现了Java程序中按照FastCGI Client的方式（替代Nginx）与PHP-FPM通信，实现Java项目+PHP的一种组合搭配，解决的问题是Java程序一般来说在代码调整后需要编译过程，而PHP可以随时调整代码随时生效，那么让Java作为项目外壳，一些易变的代码由PHP实现，在需要的时候Java程序通过FastCGI与PHP打交道就好。这套想法也是基于对Nginx+PHP交互模式的理解之上想到的。</p><p>网络中也有一些借助FastCGI的尝试与实践，譬如《<a href="http://chriswu.me/blog/writing-hello-world-in-fcgi-with-c-plus-plus/" target="_blank">Writing Hello World in FCGI with C++</a>》这篇文章，用C++实现一个FastCGI的程序，外部依然是某款Webserver来处理HTTP请求，但具体功能则有C++来实现，他们的中间交互同样适用的FastCGI。同学们有兴趣了也可以做些Geek尝试。（图7，C++实现一个FastCGI程序）<img src="https://img.mukewang.com/596e57670001f53a13250474.png" alt="图7，C++实现一个FastCGI程序"></p><p><strong><font size="4" color="#ff0000">甜品</font></strong></p><p><font size="4" color="#ff0000">通过本文的讲解，我们希望让大家看到，Nginx+PHP的工程模式下，两位主角分工明确，Nginx负责承载HTTP请求的响应与返回，以及超时控制记录日志等HTTP相关的功能，而PHP则负责处理具体请求要做的业务逻辑，它们俩的这种合作模式也是常见的分层架构设计中的一种，在它们各有专注面的同时，FastCGI又很好的将两块衔接，保障上下游通信交互，这种通过某种协议或规范来衔接好上下游的模式，在我们日常的PHP应用开发中也有这样的思想落地，譬如我们所开发的高性能API，具体的Client到底是PC、APP还是某个其他程序，我们不关心，而这些PC、APP、第三方程序也不关心我们的PHP代码实现，他们按照API的规范来请求做处理即可。同学们是不是发现技术思想是可以在各个环节融会贯通的，是不是很兴奋？很刺激？</font></p><p><br></p>";s:8:"art_view";i:2;s:14:"art_collection";i:0;s:7:"art_hit";i:8;s:7:"art_url";s:35:"https://www.imooc.com/article/19278";s:12:"art_original";i:0;s:8:"art_from";s:6:"Win 10";s:10:"art_author";s:6:"赵雨";s:8:"art_city";s:14:"湖北武汉XX";s:16:"art_downloadnums";i:0;}s:9:" * change";a:0:{}s:7:" * auto";a:0:{}s:9:" * insert";a:0:{}s:9:" * update";a:0:{}s:21:" * autoWriteTimestamp";b:0;s:13:" * createTime";s:11:"create_time";s:13:" * updateTime";s:11:"update_time";s:13:" * dateFormat";s:11:"Y-m-d H:i:s";s:7:" * type";a:0:{}s:11:" * isUpdate";b:1;s:14:" * updateWhere";N;s:11:" * relation";N;s:16:" * failException";b:0;s:17:" * useGlobalScope";b:1;s:16:" * batchValidate";b:0;s:16:" * resultSetType";N;}}s:5:"links";i:1;s:8:"download";a:3:{i:0;O:23:"app\index\model\Article":30:{s:13:" * connection";a:0:{}s:8:" * query";N;s:7:" * name";s:7:"Article";s:8:" * table";N;s:8:" * class";s:23:"app\index\model\Article";s:8:" * error";N;s:11:" * validate";N;s:5:" * pk";N;s:8:" * field";a:0:{}s:11:" * readonly";a:0:{}s:10:" * visible";a:0:{}s:9:" * hidden";a:0:{}s:9:" * append";a:0:{}s:7:" * data";a:2:{s:6:"art_id";i:8;s:9:"art_title";s:12:"博客源码";}s:9:" * change";a:0:{}s:7:" * auto";a:0:{}s:9:" * insert";a:0:{}s:9:" * update";a:0:{}s:21:" * autoWriteTimestamp";b:0;s:13:" * createTime";s:11:"create_time";s:13:" * updateTime";s:11:"update_time";s:13:" * dateFormat";s:11:"Y-m-d H:i:s";s:7:" * type";a:0:{}s:11:" * isUpdate";b:1;s:14:" * updateWhere";N;s:11:" * relation";N;s:16:" * failException";b:0;s:17:" * useGlobalScope";b:1;s:16:" * batchValidate";b:0;s:16:" * resultSetType";N;}i:1;O:23:"app\index\model\Article":30:{s:13:" * connection";a:0:{}s:8:" * query";N;s:7:" * name";s:7:"Article";s:8:" * table";N;s:8:" * class";s:23:"app\index\model\Article";s:8:" * error";N;s:11:" * validate";N;s:5:" * pk";N;s:8:" * field";a:0:{}s:11:" * readonly";a:0:{}s:10:" * visible";a:0:{}s:9:" * hidden";a:0:{}s:9:" * append";a:0:{}s:7:" * data";a:2:{s:6:"art_id";i:25;s:9:"art_title";s:18:"焦婷的作品集";}s:9:" * change";a:0:{}s:7:" * auto";a:0:{}s:9:" * insert";a:0:{}s:9:" * update";a:0:{}s:21:" * autoWriteTimestamp";b:0;s:13:" * createTime";s:11:"create_time";s:13:" * updateTime";s:11:"update_time";s:13:" * dateFormat";s:11:"Y-m-d H:i:s";s:7:" * type";a:0:{}s:11:" * isUpdate";b:1;s:14:" * updateWhere";N;s:11:" * relation";N;s:16:" * failException";b:0;s:17:" * useGlobalScope";b:1;s:16:" * batchValidate";b:0;s:16:" * resultSetType";N;}i:2;O:23:"app\index\model\Article":30:{s:13:" * connection";a:0:{}s:8:" * query";N;s:7:" * name";s:7:"Article";s:8:" * table";N;s:8:" * class";s:23:"app\index\model\Article";s:8:" * error";N;s:11:" * validate";N;s:5:" * pk";N;s:8:" * field";a:0:{}s:11:" * readonly";a:0:{}s:10:" * visible";a:0:{}s:9:" * hidden";a:0:{}s:9:" * append";a:0:{}s:7:" * data";a:2:{s:6:"art_id";i:14;s:9:"art_title";s:62:"windows10下安装SourceTree-号称最好用的git管理工具";}s:9:" * change";a:0:{}s:7:" * auto";a:0:{}s:9:" * insert";a:0:{}s:9:" * update";a:0:{}s:21:" * autoWriteTimestamp";b:0;s:13:" * createTime";s:11:"create_time";s:13:" * updateTime";s:11:"update_time";s:13:" * dateFormat";s:11:"Y-m-d H:i:s";s:7:" * type";a:0:{}s:11:" * isUpdate";b:1;s:14:" * updateWhere";N;s:11:" * relation";N;s:16:" * failException";b:0;s:17:" * useGlobalScope";b:1;s:16:" * batchValidate";b:0;s:16:" * resultSetType";N;}}}
?>