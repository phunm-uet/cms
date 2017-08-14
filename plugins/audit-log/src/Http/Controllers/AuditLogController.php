<?php
namespace Botble\AuditLog\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\AuditLog\Repositories\Interfaces\AuditLogInterface;

class AuditLogController extends Controller
{

    /**
     * @var AuditLogInterface
     */
    protected $auditLogRepository;

    /**
     * AuditLogController constructor.
     * @param AuditLogInterface $auditLogRepository
     */
    public function __construct(AuditLogInterface $auditLogRepository)
    {
        $this->auditLogRepository = $auditLogRepository;
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getWidgetActivities()
    {
        $limit = request()->input('paginate', 10);
        $histories = $this->auditLogRepository->getModel()->orderBy('created_at', 'desc')->paginate($limit);
        return ['error' => false, 'data' => view('audit-logs::widgets.activities', compact('histories', 'limit'))->render()];
    }
}