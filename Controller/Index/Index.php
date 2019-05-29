<?php


namespace Born\SwitchUser\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepo,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->customerRepo = $customerRepo;
        $this->customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $request;
        $this->_registry = $registry;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $subUserId = $this->getRequest()->getParams();
        $this->logoutUser($subUserId);
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('company/users/');
        return $resultRedirect;
    }
    public function logoutUser($subUserId){

        $this->_registry->register('id',$this->customerSession->getCustomerId());
        $this->customerSession->logout();
        $this->loginUser($subUserId);
    }
    public function loginUser($subUserId){
        $customer = $this->customerFactory->create()->load($subUserId['id']);
        $this->customerSession->setCustomerAsLoggedIn($customer);
        // this is the Customer id of parent customer from where its coming
        $adminCustomerId = $this->_registry->registry('id');
    }
}
